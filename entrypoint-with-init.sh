#!/bin/sh

# Patch /entrypoint.sh
patch /entrypoint.sh << EOM
@@ -159,6 +159,40 @@
                     if [ "$try" -gt "$max_retries" ]; then
                         echo "installing of nextcloud failed!"
                         exit 1
+                    else
+                        echo "Running post-install user initialization"
+
+                        # Install and enable encryption apps.
+                        run_as "php /var/www/html/occ app:enable encryption"
+                        run_as "php /var/www/html/occ encryption:enable"
+                        run_as "php /var/www/html/occ app:install end_to_end_encryption"
+
+                        # Disable encrypted home storage.
+                        run_as "php /var/www/html/occ config:app:set encryption encryptHomeStorage --value 0"
+
+                        # Install and enable 2FA app.
+                        run_as "php /var/www/html/occ app:install twofactor_totp"
+
+                        # Enable external storage and create mounts.
+                        run_as "php /var/www/html/occ app:enable files_external"
+                        if [ -f /opt/nextcloud/custom-mounts.json ]; then
+                            echo "Mounting custom external storage"
+                            run_as "php /var/www/html/occ files_external:import /opt/nextcloud/custom-mounts.json" || echo "Custom mounts failed"
+                        fi
+
+                        # Auto-create users if provided.
+                        for f in \$(seq 1 100); do
+                            username_var="AUTOCREATE_USERNAME\$f"
+                            password_var="AUTOCREATE_PASSWORD\$f"
+                            username=\$(eval echo \\\${\$username_var:-})
+                            password=\$(eval echo \\\${\$password_var:-})
+                            if [ -n "\${username}" ] && [ -n "\${password}" ]; then
+                                echo "Creating user \${username}"
+                                export OC_PASS=\${password}
+                                run_as "php /var/www/html/occ user:add --password-from-env --group=\"users\" \${username}"
+                            fi
+                        done
+                        echo "Finished post-install user initialization"
                     fi
                     if [ -n "${NEXTCLOUD_TRUSTED_DOMAINS+x}" ]; then
                         echo "setting trusted domains…"
EOM

# Run /entrypoint.sh
/entrypoint.sh $@

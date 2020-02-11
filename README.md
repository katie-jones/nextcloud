# Nextcloud

Docker service running nextcloud.

# Setup

## Basic Install

1. Create `.env` file (see `sample.env`).
1. Run `docker-compose up`.
1. Go to domain in browser and install nextcloud (create admin account).

## Encryption

End-to-end encryption is not working with Linux desktop app v2.5, so it is not enabled.

1. Enable at-rest encryption:
    1. Click on the user icon in the top right corner and click "Apps".
    1. Click on the search button in the top right corner and type "encryption".
    1. Enable the following apps: "Default encryption module".
    1. Click on the user icon in the top right corner and click "Settings".
    1. In the left-hand menu, click on "Security" under section "Administration".
    1. Check the option "Enable server-side encryption" and confirm by clicking "Enable encryption".

## Email

Email can be sent using a bare-bones SMTP server running as part of the docker-compose app.

1. Click on the user icon in the top right corner and click "Settings".
1. In the left-hand menu, click on "Basic settings" under section "Administration".
1. Fill in the section "Email server" as follows:
    - Send mode: SMTP
    - Encryption: None
    - From address: noreply@mydomain.com
    - Authentication method: None
    - Server address: mail:25

## Keeweb

1. Download and install the keeweb app:
    1. Use `docker volume ls` to find the volume containing your custom apps (e.g. "nextcloud_apps").
    1. Use `docker volume inspect <volname>` to find the local directory where this data is stored.
    1. Run `wget https://github.com/jhass/nextcloud-keeweb/releases/download/v0.5.0/keeweb-0.5.0.tar.gz -O - | sudo tar -xz -C <localdir>` to install the app.
    1. Open Nextcloud as an admin.
    1. Click on the user icon in the top right corner and click "Apps".
    1. Click on the search button in the top right corner and type "keeweb".
    1. Enable the following apps: "Keeweb".
1. Fix MIMEtype detection:
    1. Use `docker volume ls` to find the volume containing your nextcloud config (e.g. "nextcloud_config").
    1. Use `docker volume inspect <volname>` to find the local directory where this data is stored.
    1. Change to the directory found above and create/edit the file `mimetypemapping.json` (as root/with sudo) to contain the following content: ```
{
  "kdbx": ["application/x-kdbx"]
}```.
    1. Use `docker container ls` to identify the container running nextcloud (e.g. "nextcloud_nextcloud_1").
    1. Run the following command to re-read the config files: `docker exec -it -u www-data <containername> php occ maintenance:mimetype:update-js`.

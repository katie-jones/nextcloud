<?php

// Force correct protocol.
$CONFIG = array (
'overwriteprotocol' => 'https',
);

// Add email settings if provided.
if (getenv('NEXTCLOUD_SYSTEM_EMAIL')) {
    list($emailname,$emaildomain) = explode('@',getenv('NEXTCLOUD_SYSTEM_EMAIL'));
    if (strlen($emailname) and strlen($emaildomain)) {
        $CONFIG['mail_domain'] = $emaildomain;
        $CONFIG['mail_from_address'] = $emailname;
        $CONFIG['mail_smtphost'] = 'smtp';
        $CONFIG['mail_smtpport'] = 25;
    }
}

// Add proxy settings.
$CONFIG['trusted_proxies'] = ['127.0.0.1'];

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

// Add spaces settings.
if (getenv('NEXTCLOUD_S3_BUCKET')) {
    $CONFIG['objectstore'] = array(
            'class' => '\\OC\\Files\\ObjectStore\\S3',
            'arguments' => array(
                    'bucket' => getenv('NEXTCLOUD_S3_BUCKET'),
                    'key'    => getenv('NEXTCLOUD_S3_KEY'),
                    'secret' => getenv('NEXTCLOUD_S3_SECRET'),
                    'hostname' => getenv('NEXTCLOUD_S3_HOSTNAME'),
                    'region' => getenv('NEXTCLOUD_S3_REGION'),
                    'port' => 443,
                    'use_ssl' => true,
                    'use_path_style'=>false,
                    'autocreate'=>false,
            ),
    );
}

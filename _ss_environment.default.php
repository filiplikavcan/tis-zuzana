<?php

global $_FILE_TO_URL_MAPPING;
$_FILE_TO_URL_MAPPING[__DIR__] = 'http://tis-zuzana.dev';

define('SS_DATABASE_CLASS', 'MySQLDatabase');
define('SS_DATABASE_SERVER', '127.0.0.1');
define('SS_DATABASE_USERNAME', 'root');
define('SS_DATABASE_PASSWORD', '');

define('SS_ENVIRONMENT_TYPE', 'dev');

define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_PROTOCOL', 'tls');
define('SMTP_LOGIN', '');
define('SMTP_PASSWORD', '');

define('MAIL_BASE_URL', 'http://tis-zuzana.dev/');
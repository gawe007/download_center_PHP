<?php
declare(strict_types=1);

define("DB_USER", "root");

define("DB_PASS", "");

define("DB_NAME", "download_center");

define("DB_SERVER", "localhost");

define("HOSTNAME_FULL_URL", "http://localhost/download_center");

define("HOSTNAME_DOMAIN", "localhost/download_center");

define("SESSION_TIME_LIFE", 1800);

define('UPLOAD_DIR', realpath(__DIR__ . '/../../../files'));

define('BANNED_FILE_EXTENSION', ['php', 'c', 'vbs', 'dll', 'bat']);

define('UPLOAD_MAX_SIZE', ini_get('upload_max_filesize'));

define('POST_MAX_SIZE', ini_get('post_max_size'));

<?php
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_PORT', getenv('DB_PORT') ?: '5432');
define('DB_NAME', getenv('DB_NAME') ?: 'tutoring');
define('DB_USER', getenv('DB_USER') ?: 'postgres');

$pwd = getenv('DB_PASSWORD');
if ($pwd === false || $pwd === '') {
    $pwd = getenv('DB_PASS') ?: '';
}
define('DB_PASS', $pwd);

date_default_timezone_set('Europe/Warsaw');

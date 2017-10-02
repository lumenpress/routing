<?php

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$packagePath = realpath(dirname(PHPUNIT_COMPOSER_INSTALL).'/lumenpress/testing');

system("php $packagePath/tests/includes/install.php");

require $packagePath.'/tests/wp-tests-load.php';

putenv('APP_DEBUG='.(WP_DEBUG ? 'true' : 'false'));
putenv('APP_TIMEZONE='.(get_option('timezone_string') ?: 'UTC'));
putenv('DB_HOST='.DB_HOST);
putenv('DB_DATABASE='.DB_NAME);
putenv('DB_USERNAME='.DB_USER);
putenv('DB_PASSWORD='.DB_PASSWORD);
putenv('DB_PREFIX='.$GLOBALS['table_prefix']);

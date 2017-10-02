<?php

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

require realpath(dirname(PHPUNIT_COMPOSER_INSTALL).'/lumenpress/testing/tests/includes/bootstrap.php');

putenv('APP_DEBUG='.(WP_DEBUG ? 'true' : 'false'));
putenv('APP_TIMEZONE='.(get_option('timezone_string') ?: 'UTC'));
putenv('DB_HOST='.DB_HOST);
putenv('DB_DATABASE='.DB_NAME);
putenv('DB_USERNAME='.DB_USER);
putenv('DB_PASSWORD='.DB_PASSWORD);
putenv('DB_PREFIX='.$GLOBALS['table_prefix']);

<?php
/*
 * Set up our application and require files
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__."/functions.php";
require_once __DIR__."/connection.php";

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

/*require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/functions_book.php';
require_once __DIR__ . '/functions_user.php';
require_once __DIR__ . '/functions_vote.php';
require_once __DIR__ . '/functions_auth.php';
*/
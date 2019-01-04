<?php
/*
 * This file is part of the Salut framework
 * (c) 2018 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Define some constants
define('TEST_PATH', realpath(__DIR__));
define('APPLICATION_PATH', realpath(__DIR__ . "/.."));
define('START_TIME', microtime(true));

// Show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Register the autoloader
$loader = require APPLICATION_PATH . "/vendor/autoload.php";
// $loader->addPsr4('Ra5k\\Salud\\Test\\', __DIR__ . '/src');

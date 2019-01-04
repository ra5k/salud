<?php
/*
 * This file is part of the Salud library
 * (c) 2018 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Define some constants
define('TEST_PATH', realpath(__DIR__));
define('APPLICATION_PATH', realpath(__DIR__ . "/../.."));
define('START_TIME', microtime(true));

// Show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Register the autoloader
$loader = require APPLICATION_PATH . "/vendor/autoload.php";
$loader->addPsr4('Ra5k\\Standalone\\', __DIR__ . '/lib');


// [Import namespaces]
use Ra5k\Standalone\{Script, Context, Burst};

// [INIT]
set_error_handler(new Burst);
$context = new Context();
$resource = new SplFileInfo($context->origin());
$path = $context->path();

// [Dispatch]
if ($resource->isFile()) {
    // Let the Webserver handle Non-PHP files
    return false;
} else {
    // Switch context
    $context = new Context('/scripts');
    $resource = new SplFileInfo($context->origin());
    if ($resource->isDir()) {
        // List directory
        $dir = $resource->getPathname();
        $script = new Script('/share/list.php', $context);
        $script->prepare()->render(['directory' => $dir, 'path' => $path, 'title' => "Scripts"]);
    } else {
        $translated = new SplFileInfo($resource->getPathname() . '.php');
        if ($translated->isFile()) {
            // Load the view
            error_log("[200]: $path", 4);
            $script = new Script("$path.php", $context);
            $script->prepare()->render();
        } else {
            // Error
            $status = $resource->isReadable() ? 403 : 404;
            $message = $context->message($status);
            $script = new Script('/share/error.php', $context);
            error_log("[$status] $message: $path", 4);
            header("HTTP/1.1 $message");
            $script->prepare()->render(['message' => $message, 'status' => $status]);
        }
    }
}

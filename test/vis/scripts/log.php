<?php

    namespace Ra5k\Salud\Log;


    $path = TEST_PATH . '/../temp/vis.log';

    // Truncate
    $file = fopen($path, 'w'); fclose($file);

    // Log
    $write = function ($path) {
        $log = new Stream($path);
        $log->info("Info message");
        $log->alert("Alert message");
    };

    $write($path);
    $content = htmlspecialchars(file_get_contents($path));
?>

<h2>Log</h2>

<pre><?= $content ?></pre>

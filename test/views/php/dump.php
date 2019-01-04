<?php 

foreach (token_get_all(file_get_contents("layout.php")) as $token) {
    if (is_array($token)) {
        $token[0] = token_name($token[0]);
    }
    echo json_encode($token, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
}


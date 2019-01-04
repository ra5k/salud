<?php

	// Prep
	$action = filter_input(INPUT_GET, 'action');
	$location = filter_input(INPUT_SERVER, 'REQUEST_URI');
    $cut = strrpos($location, '?');
    if ($cut !== false) {
        $location = substr($location, 0, $cut);
    }

	// Processing
	switch ($action) {
		case 'reset':
			opcache_reset();
			header("Location: $location");
			exit;
	}

    $info = opcache_get_status();
    $scriptList = $info['scripts'];
    usort($scriptList, function ($a, $b) {
        return ($b['hits'] - $a['hits']);
    });
    $out = function ($value) {
        echo htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
    };
    $cache = $info['memory_usage']['used_memory'];
    $mem = memory_get_peak_usage();

?>
<h1>OpCache Stats</h1>

<p class="actions">
    <?php printf('<a href="%s">[Reset]</a>', "?action=reset"); ?>
</p>
<p class="agg">
    <label>Cache memory used:</label> <span class="mem"><?php $out(number_format($cache / 1024)); ?> KiB</span>
</p>
<!--
<div class="agg">
    <label>PHP memory used:</label> <span class="mem"><?php $out(number_format($mem / 1024)); ?> KiB</span>
</div>
-->

<table>
    <tr>
        <th>Num</th>
        <th>Script</th>
        <th>Mem</th>
        <th>Hits</th>
    </tr>
    <?php foreach ($scriptList as $offset => $script): ?>
    <tr>
        <td><?php $out($offset + 1); ?></td>
        <td><?php $out($script['full_path']); ?></td>
        <td><?php $out($script['memory_consumption']); ?></td>
        <td><?php $out($script['hits']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>

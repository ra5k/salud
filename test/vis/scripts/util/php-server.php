<?php
/*
 * This file is part of the Ra5k Salut library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$cell = function ($string, $max_with = 80) {
    if (is_array($string)) {
        $string = json_encode($string, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    $content = $this->escape($string);
    if (strlen($string) > $max_with && preg_match('/^[^\s]+$/', $string)) {
        $display = $this->escape(substr($string, 0, $max_with)) . ' &hellip;';
        printf('<span title="%s">%s</span>', $content, $display);
    } else {
        printf('<span>%s</span>', $content);
    }
};


?>
<h2>SERVER Variables</h2>
<table style="font-size:90%">
    <thead>
        <tr>
            <th>Variable</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($_SERVER as $var => $value): ?>
        <tr>
            <td><?= $this->escape($var) ?></td>
            <td><?= $cell($value) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
/*
 * This file is part of the Ra5k Salut library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$cell = function ($string, $max_with = 30) {
    $content = $this->escape($string);
    if (strlen($string) > $max_with && preg_match('/^[^\s]+$/', $string)) {
        $display = $this->escape(substr($string, 0, $max_with)) . ' &hellip;';
        printf('<span title="%s">%s</span>', $content, $display);
    } else {
        printf('<span>%s</span>', $content);
    }
};

$access = function ($flags) {
    $indicators = [];
    if ($flags == 07) {
        $indicators[] = 'ALL';
    } else {
        if ($flags & 01) {
            $indicators[] = 'USER';
        }
        if ($flags & 02) {
            $indicators[] = 'PREDIR';
        }
        if ($flags & 04) {
            $indicators[] = 'SYSTEM';
        }
    }
    return implode(', ', $indicators);
};

?>
<h2>PHP Settings</h2>
<table style="font-size:90%">
    <thead>
        <tr>
            <th>Variable</th>
            <th>Global Value</th>
            <th>Local Value</th>
            <th>Access Level</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (ini_get_all() as $var => $info): ?>
        <tr>
            <td><?= $this->escape($var) ?></td>
            <td><?= $cell($info['global_value']) ?></td>
            <td><?= $cell($info['local_value']) ?></td>
            <td><?= $access($info['access']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

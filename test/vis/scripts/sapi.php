<?php
    /*
     * This file is part of the Ra5k Salud library
     * (c) 2017 GitHub/ra5k
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

     use Ra5k\Salud\System\Sapi;

     // include __DIR__ . "/../../bootstrap.php";
?>

<h2>Quick tests</h2>

<h3>Server Context</h3>

<h4>Path</h4>
<?php var_dump(Sapi::path()); ?>

<h4>Prefix</h4>
<?php var_dump(Sapi::prefix()); ?>

<h4>Suffix</h4>
<?php var_dump(Sapi::suffix()); ?>

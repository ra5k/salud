<?php
    /*
     * This file is part of the Ra5k Salut library
     * (c) 2018 GitHub/ra5k
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

     use Ra5k\Salud\System\Location;

?>

<h2>Quick tests</h2>

<?php
    $l = new Location();
    var_dump($l->path());
    var_dump($l->prefix());
    var_dump($l->suffix());
?>

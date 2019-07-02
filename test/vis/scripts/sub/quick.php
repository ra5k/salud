<?php
    /*
     * This file is part of the Ra5k Salut library
     * (c) 2017 GitHub/ra5k
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

     use Ra5k\Salud\Sapi;

?>

<h2>Quick tests</h2>

<h3>Server Context</h3>
<?php
    $c = new Sapi\Auto();
    var_dump($c->prefix());
    var_dump($c->suffix());
?>

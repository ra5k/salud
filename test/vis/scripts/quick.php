<?php
    /*
     * This file is part of the Ra5k Salut library
     * (c) 2018 GitHub/ra5k
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

     use Ra5k\Salud\System\Context;

?>

<h2>Quick tests</h2>

<?php
    $c = new Context();
    var_dump($c->path());
    var_dump($c->prefix());
    var_dump($c->suffix());
?>

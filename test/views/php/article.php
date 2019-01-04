<?php $script->extend("partials/layout.php", function ($base) { ?>


    <?php $base->block("header", function () { ?>
        <nav>HEADER</nav>
    <?php }) // block:main ?>


    <?php $base->block("main", function () { ?>

        <div class="main">
            <?= $this->call('partials/time.php') ?>
        </div>

    <?php }) // block:main ?>


    <?php $base->block("footer", 'FOOTER STROM STRING'); ?>

<?php }); // extends


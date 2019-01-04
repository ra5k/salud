<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?= $this->base->ref('assets/styles.css') ?>" media="all" />
        <title><?= $this->title->push('Ra5k Salud Tests')->join(' - ')->esc(); ?></title>
    </head>
    <body>
        <header>
            <div class="container">
                <div class="brand"><a href="<?= $this->base->ref() ?>">Visual Tests</a></div>
            </div>
        </header>
        <main>
            <div class="container">
                <?= $this->content->display(); ?>
            </div>
        </main>
        <footer>
            <div class="container">
                <p><small><?php
                    $time = (microtime(true) - START_TIME) * 1000;
                    $memory = memory_get_peak_usage();
                    printf('<strong>Time</strong>: %.2f ms, <strong>Memory</strong>: %d Byes',
                            $time, $memory);
                ?></small></p>
            </div>
        </footer>
    </body>
</html>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>The Title</title>
    </head>
    <body>
        <header>
            <?php $script->block("header"); ?>

        </header>
        
        <main>
        <?php $script->block("main", function () { ?>
            
            <div class="left">LEFT</div>
            <div class="right">RIGHT</div>
    
        <?php }); ?>

        </main>

        <footer>
            <?php $script->block("footer"); ?>

        </footer>
    </body>
</html>

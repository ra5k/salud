<?php

    ini_set('xdebug.var_display_max_depth', 6);

    use Ra5k\Salud\Upload;

?>

<h2>Upload</h2>

<form class="form" method="post" enctype="multipart/form-data">
    <div class="group">
        <label>File 1: <input type="file" name="file-group[teaser]" /></label>
    </div>
    <div class="group">
        <label>File 2: <input type="file" name="file-group[main]" /></label>
    </div>
    <div class="group">
        <button type="submit">Upload</button>
    </div>
</form>

<hr/>

<div style="display: flex; flex-direction: row;">
    <section style="margin: 0 1rem">
        <h2>$_FILES</h2>
        <pre><?= var_dump($_FILES) ?></pre>
    </section>
    <section style="margin: 0 1rem">
        <h2>Uploads</h2>
        <pre><?= var_dump(Upload\Files::tree($_FILES)) ?></pre>
    </section>
</div>
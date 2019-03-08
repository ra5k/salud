<?php

    ini_set('xdebug.var_display_max_depth', 6);

    use Ra5k\Salud\System\Upload;

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

<pre>
<?= var_dump(Upload::origin()) ?>
<?= var_dump(Upload::tree()) ?>
</pre>

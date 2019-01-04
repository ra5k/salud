<?php

    use Ra5k\Salud\Request;
    $req = new Request\Sapi();

?>

<h2>Standard Request <small>(via <code>filter_input</code>)</small></h2>
<ul>
    <li><a href="?q[]=1&q[]=2">(Reload with multi-params)</a></li>
    <li><a href="?q=search+phrase&i=en">(Reload with single-params)</a></li>
</ul>

<h3>Debug output</h3>
<?php var_dump($req->get('q')) ?>
<?php var_dump($req->get('i')) ?>

<hr/>


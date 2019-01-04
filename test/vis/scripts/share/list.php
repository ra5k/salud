<?php
    /*
     * This file is part of the Ra5k Salut library
     * (c) 2017 GitHub/ra5k
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

    use FilesystemIterator as Fsi;

    // Create the directory model
    //
    $itemList = [];
    $directory = $this->directory;
    $path = $this->path;
    $base = $this->base;

    $parent = $path->up();
    $flags = Fsi::SKIP_DOTS | Fsi::UNIX_PATHS | Fsi::KEY_AS_FILENAME;
    //
    foreach (new Fsi($directory, $flags) as $name => $file) {
        if ($name == 'index.php' || $name == 'lib' || $name == 'share') {
            continue;
        }
        $ext = $file->getExtension();
        if ($file->isFile() && $ext != 'php') {
            continue;
        }
        $now = new DateTime("now");
        $mod = new DateTime("@" . $file->getMTime());
        $span = $this->node($now->diff($mod));
        $head = trim($path, '/');
        $leaf = $file->getBasename('.php');
        $href = $base->ref("$head/$leaf");
        $meta = $this->config($file->getPathname());
        //
        $itemList[$name] = $this->node([
            'name' => $name,
            'group' => ($file->isDir() ? 0 : 1),
            'type' => ($file->isDir() ? 'dir' : (($name != $leaf) ? 'php' : 'file')),
            'href' => $href,
            'size' => $file->getSize(),
            'mod_span' => ($span->age()) ?: $mod->format('Y-m-d H:i:s'),
            'mod_stamp' => $mod->format('Y-m-d H:i:s'),
            'description' => $meta->description->core(),
        ]);
    }
    usort($itemList, function ($a, $b) {
        $d = $a->group->core() - $b->group->core();
        return $d ?: strcmp($a->name, $b->name);
    });


?>
<h3><?= $this->path->esc() ?></h3>
<table>
    <thead>
        <tr>
            <th>Filename</th>
            <th>Size</th>
            <th>Modified</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($parent && $path != '/'): ?>
        <tr>
            <td>
                <a href="<?= $base->ref($parent) ?>" class="type-up"><em>up</em></a>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php endif; ?>
        <?php foreach ($itemList as $file): ?>
        <tr>
            <td>
                <?= $file->resolve('<a href="{href}" class="type-{type}">{name}</a>') ?>
            </td>
            <td>
                <?= $file->size, ' B' ?>
            </td>
            <td>
                <?= $file->resolve('<time datetime="{mod_stamp}" title="{mod_stamp}">{mod_span}</time>') ?>
            </td>
            <td><?= $file->description->esc() ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

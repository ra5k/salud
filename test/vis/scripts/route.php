<?php

    ini_set('xdebug.var_display_max_depth', 6);

    use Ra5k\Salud\{Request, Response, Service, Fork, Indicator};
    use Ra5k\Salud\{Response as Re, Fork as F};
    $current = new Request\Sapi();

    $url = $current->get('url');
    $esc = function ($string) {
        return htmlspecialchars($string);
    };

    $special = new class() implements Service {
        public function handle(Request $request): Response
        {
            return new Re\Instant("Special: " . $request->attribute('key'));
        }
    };

?>

<h2>Route Chain</h2>

<form method="get">
    <input type="text" name="url" value="<?= $esc($url) ?>" size="80" placeholder="URL Path" />
    <button>Reload</button>
</form>

<hr/>

<?php

    $req = new Request\Instant($url);
    $main = new F\Chain([
        new F\Regex('@ ^ /admin ($|(?=/)) @x', [
            new F\Regex('@ ^ /user $ @x', new Re\Instant("User")),
            new F\Regex('@ ^ /config @x', new Re\Instant("Config")),
            new F\Regex('@ ^ /special (/(?<key>[\w]+))? @x', $special),
            new F\Regex('@ ^ /dynamic @x', function (Request $request) {
                return new Re\Instant("DYNAMIC");
            })
        ]),
        new F\Prefix('/about', [
            new F\Prefix('', $special),
            new F\Prefix('/us', new Re\Instant('US')),
        ])
    ]);
    $tar = $main->route($req, []);    
?>
<pre><?= var_dump($tar); ?></pre>


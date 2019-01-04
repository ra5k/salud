<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Test\Service;

// [imports]
use Ra5k\Salud\Test\BaseTestCase;
use Ra5k\Salud\{Request as Rq, Service as Srv, Fork as F};


/**
 *
 *
 *
 */
class ComplexTest extends BaseTestCase
{

    public function testRoute1()
    {
        $request = new Rq\Instant('/');
        $target = $this->main()->route($request, []);
        $expected = ['title' => 'HOME', 'body' => ['lang' => null]];
        $json = $this->render($target);
        $this->assertEquals($expected, json_decode($json, true));
    }

    public function testRoute2()
    {
        $request = new Rq\Instant('/en');
        $target = $this->main()->route($request, []);
        $expected = ['title' => 'LANG', 'body' => ['lang' => 'en']];
        $json = $this->render($target);
        $this->assertEquals($expected, json_decode($json, true));
    }

    public function testRoute3()
    {
        $request = new Rq\Instant('/en/');
        $target = $this->main()->route($request, []);
        $expected = ['title' => 'LANG', 'body' => ['lang' => 'en']];
        $json = $this->render($target);
        $this->assertEquals($expected, json_decode($json, true));
    }

    public function testRoute10()
    {
        $request = new Rq\Instant('/en/countries/abc-123');
        $target = $this->main()->route($request, []);
        $expected = ['title' => 'COUNTRIES', 'body' => ['lang' => 'en', 'action' => 'abc-123']];
        $json = $this->render($target);
        $this->assertEquals($expected, json_decode($json, true));
    }

    /**
     * @return F\Chain
     */
    private function main()
    {
        $countries = new Srv\Dump(['lang', 'action'], 'COUNTRIES');
        return new F\Chain([
            new F\Prefix('/', new Srv\Dump(['lang'], 'HOME')),
            new F\Regex('@ ^ /(?<lang>[a-z]{2}) ($|(?=/)) @x', [
                new F\Prefix('/', new Srv\Dump(['lang'], 'LANG')),
                new F\Prefix('/projects', new Srv\Dump(['lang'], 'PROJECTS')),
                new F\Regex('@ ^ /countries(/(?<action>[\w\-]+))? $ @x', $countries),
            ]),
        ]);
    }

    /**
     * @return string
     */
    private function render($target)
    {
        ob_start();
        if ($target->isMatch()) {
            $target->response()->write();
        } else {
            echo 'false', PHP_EOL;
        }
        return ob_get_clean();
    }


}

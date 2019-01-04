<?php
/*
 * This file is part of the Salud library
 * (c) 2018 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Test;


/**
 *
 *
 *
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        ini_set('xdebug.var_display_max_depth', 10);
    }

    protected function jsonBlock($var)
    {
        $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        return json_encode($var, $flags);
    }

    protected function jsonLine($var)
    {
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        return json_encode($var, $flags);
    }

}

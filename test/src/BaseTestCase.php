<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Test;


// [imports]
use PHPUnit\Framework\TestCase;

/**
 *
 *
 *
 */
abstract class BaseTestCase extends TestCase
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

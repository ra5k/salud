<?php
/*
 * This file is part of the Salut framework
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Test\Uri;

// [imports]
use Ra5k\Salud\Uri;
use Ra5k\Salud\Test\BaseTestCase;

/**
 *
 *
 */
class UtilTest extends BaseTestCase
{

    public function testZero1()
    {
        new Uri\Zero;
    }

    public function testWithHost1()
    {
        $uri = new Uri\WithHost(new Uri\Std('http://initial'), 'replaced');
        $this->assertEquals("http://replaced", $uri->toString());
    }
    
}

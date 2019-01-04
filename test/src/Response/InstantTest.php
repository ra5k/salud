<?php
/*
 * This file is part of the Salud library
 * (c) 2018 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Test\Response;

// [imports]
use Ra5k\Salud\Test\BaseTestCase;
use Ra5k\Salud\Response;


/**
 *
 *
 *
 */
class InstantTest extends BaseTestCase
{

    public function test1()
    {
        $re = new Response\Instant("HELLO WORLD");
        var_dump($re);
    }

}

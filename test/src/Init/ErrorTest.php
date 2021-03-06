<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Test\Init;

// [imports]
use Ra5k\Salud\Test\BaseTestCase;
use Ra5k\Salud\Init\Error;
use Exception;

/**
 *
 *
 *
 */
class ErrorTest extends BaseTestCase
{

    public function test1()
    {
        $error = new Exception("TEST");
        $page = new Error\DiscretePage($error);
        $html = $page->page();
        // echo $html->content();
        $this->assertTrue(true);
    }

}

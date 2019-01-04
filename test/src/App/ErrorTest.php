<?php
/*
 * This file is part of the Salud library
 * (c) 2018 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Test\App;

// [imports]
use Ra5k\Salud\Test\BaseTestCase;
use Ra5k\Salud\App\Error;
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
    }

}

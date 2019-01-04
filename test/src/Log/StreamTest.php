<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Test\Log;

// [imports]
use Ra5k\Salud\Test\BaseTestCase;
use Ra5k\Salud\Log;


/**
 *
 *
 *
 */
class StreamTest extends BaseTestCase
{

    public function test1()
    {        
        $log = new Log\Stream('php://stdout');
        // $log->error("Hello");
        $this->assertTrue(true);
    }

}

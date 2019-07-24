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
use Ra5k\Salud\{Init, Service, Request, Response};
use RuntimeException, Throwable;

/**
 *
 *
 *
 */
class GenericExceptionTest extends BaseTestCase
{

    public function test1()
    {
        $target = TEST_PATH . '/temp/log-trace.log';
        if (file_exists($target)) {
            unlink($target);
        }
        $service = new class() implements Service {
            public function handle(Request $request): Response
            {
                throw new RuntimeException("Bang!");
            }
        };
        $init = new Init\Generic([
            'log' => [
                'filename' => $target,
                'trace' => true
            ]
        ]);
        
        ob_start();
        $init->run($service);
        ob_end_clean();
        
        $lines = 0;
        $trace = fopen($target, 'r');
        while (!feof($trace)) {
            fgets($trace);
            $lines++;
        }
        
        $this->assertTrue($lines > 1);
    }

}

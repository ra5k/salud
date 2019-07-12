<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Test\Pattern;


use Ra5k\Salud\Pattern\FileList;
use Ra5k\Salud\Test\BaseTestCase;

/**
 *
 *
 *
 */
class FileListTest extends BaseTestCase
{

    public function testOne()
    {
        $p = new FileList([
            '**/xyz',
            '/abc/**',
            'a/**/z',
            '! /abc/except',
            'ff*/gg'
        ]);
        // var_dump($p);
        $this->assertTrue($p->test('xyz'));
        $this->assertFalse($p->test('123-xyz'));
        $this->assertTrue($p->test('123/xyz'));
        $this->assertTrue($p->test('/abc'));
        $this->assertTrue($p->test('/abc/misc'));
        $this->assertFalse($p->test('/abc/except'));
        $this->assertTrue($p->test('a/z'));
        $this->assertTrue($p->test('a/m/z'));
        $this->assertFalse($p->test('az'));
        $this->assertFalse($p->test('amz'));
        $this->assertTrue($p->test('ff00/gg'));
        $this->assertFalse($p->test('ff/00/gg'));
    }    
    
}

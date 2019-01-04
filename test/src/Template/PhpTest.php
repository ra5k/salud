<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Test\Tempate;

// [imports]
use Ra5k\Salud\Test\BaseTestCase;
use Ra5k\Salud\Template;


/**
 *
 *
 *
 */
class PhpTest extends BaseTestCase
{

    public function test1()
    {
        $t = new Template\Php('article.php', $this->base() . '/php');        
        ob_start();
        $t->render(["time" => 123456]);
        $content = ob_get_clean();        
        $this->assertXmlStringEqualsXmlFile($this->base() . '/expected/article.html', $content);
    }

    /**
     * @return string
     */
    private function base()
    {
        return TEST_PATH . '/views';
    }

}

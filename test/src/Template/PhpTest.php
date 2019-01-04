<?php
/*
 * This file is part of the Salud library
 * (c) 2018 GitHub/ra5k
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
        $t = new Template\Php('article.php', $this->base());
        $t->render(["time" => time()]);
    }

    /**
     * @return string
     */
    private function base()
    {
        return TEST_PATH . '/views/php';
    }

}

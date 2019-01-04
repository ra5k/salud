<?php
/*
 * This file is part of the Ra5k Salut library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Standalone;

// [imports]


/**
 * A variable container
 *
 */
final class View
{

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $k => $v) {
            $this->$k = new Param($v);
        }
    }

    /**
     * @param string $name
     * @return Param
     */
    public function __get($name)
    {
        return new Param(null, false);
    }

    /**
     * @param mixed $data
     * @return Param
     */
    public function node($data)
    {
        return new Param($data);
    }

    /**
     * @param string $base
     * @return Param
     */
    public function config($base)
    {
        $config = new Config($base);
        return $config->root();
    }

    /**
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
    }

}

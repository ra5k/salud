<?php

/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\System;


/**
 *
 *
 *
 */
final class Upload
{
    /**
     * @var array
     */
    private static $origin;

    /**
     * Captures the super global $_FILES
     *
     * @global array $_FILES
     * @return boolean
     */
    public static function capture()
    {
        global $_FILES;
        if (isset($_FILES)) {
            self::$origin = (array) $_FILES;
            $defined = true;
        } else {
            self::$origin = [];
            $defined = false;
        }
        return $defined;
    }

    /**
     * Returns (and saves) the original the contents of the super global $_FILES
     */
    public static function origin()
    {
        if (self::$origin === null) {
            self::capture();
        }
        return self::$origin;
    }

    /**
     * @param array $input
     * @return array
     */
    public static function tree(array $input = null)
    {
        if ($input === null) {
            $input = self::origin();
        }
        $tree = [];
        foreach ($input as $field => $spec) {
            if (is_array($spec)) {
                $target =& $tree[$field];
                foreach ($spec as $property => $value) {
                    self::seep($value, $property, $target);
                }
            }
        }
        return $tree;
    }


    private static function seep($value, $key, &$target)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $t =& $target[$k];
                self::seep($v, $key, $t);
            }
        } else {
            $target[$key] = $value;
        }
    }

}

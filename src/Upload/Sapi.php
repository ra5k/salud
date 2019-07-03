<?php

/*
 * This file is part of the Salut library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Upload;

// [imports]
use Ra5k\Salud\{Upload, Input};


/**
 *
 *
 * @deprecated
 */
final class Sapi implements Upload
{
    /**
     * An array with the keys as used by the $_FILES array
     * @var array
     */
    private $info;

    /**
     * @param array $upload
     */
    public function __construct(array $upload)
    {
        $this->info = $upload;
    }

    /**
     * @return int
     */
    public function error(): int
    {
        return $this->info['error'] ?? UPLOAD_ERR_OK;
    }

    /**
     * @return string
     */
    public function temp(): string
    {
        return $this->info['tmp_name'] ?? '';
    }

    /**
     * @param string $destination
     * @return bool
     */
    public function moveTo(string $destination): bool
    {
        $temp = $this->temp();
        if ($temp) {
            $status = move_uploaded_file($temp, $destination);
        } else {
            $status = false;
        }
        return $status;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->info['name'] ?? '';
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->info['size'] ?? -1;
    }

    /**
     * @return Input\Forward
     */
    public function stream(): Input\Forward
    {
        $filename = $this->info['tmp_name'] ?? '';
        if ($filename && is_uploaded_file($filename)) {
            $stream = new Input\File($filename, 'r');
        } else {
            $stream = new Input\Zero;
        }
        return $stream;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->info['type'] ?? '';
    }

    /**
     * @param array $input  information on the form of the $_FILES super global
     * 
     * @return array
     */
    public static function tree(array $input = null)
    {
        return self::promoted(self::format($input));
    }

    /**
     * @param array $struct
     * @return array
     */
    private static function promoted(array $struct)
    {
        $leaf = false;
        $children = [];
        foreach ($struct as $k => $v) {
            if (is_array($v)) {
                $children[$k] = self::promoted($v);
            } else {
                $leaf = true;
            }
        }
        return ($leaf) ? new self($struct) : $children;
    }

    /**
     * @param array $data
     * @return array
     */
    private static function format(array $data)
    {
        $tree = [];
        foreach ($data as $field => $spec) {
            if (is_array($spec)) {
                $target =& $tree[$field];
                foreach ($spec as $property => $value) {
                    self::seep($value, $property, $target);
                }
            }
        }
        return $tree;
    }

    /**
     * @param mixed $value
     * @param string $key
     * @param array $target
     */
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

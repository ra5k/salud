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
use SplFileInfo;


/**
 *
 *
 *
 */
final class Config
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Param
     */
    private $data;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return Param
     */
    public function root()
    {
        if ($this->data === null) {
            $this->data = new Param($this->load($this->base()));
        }
        return $this->data;
    }

    /**
     * @return string
     */
    public function base()
    {
        $origin = new SplFileInfo($this->path);
        $ext = $origin->getExtension();
        return $origin->getPath() . '/' . $origin->getBasename(".$ext");
    }

    /**
     * @param string $base
     */
    private function load($base)
    {
        $data = [];
        $ini_file = "{$base}.ini";
        $json_file = "{$base}.json";
        //
        if (file_exists($ini_file)) {
            $mode = defined('INI_SCANNER_TYPED') ? INI_SCANNER_TYPED : INI_SCANNER_NORMAL;
            $data = parse_ini_file($ini_file, true, $mode);
        } elseif (file_exists($json_file)) {
            $json = file_get_contents($json_file);
            $data = json_decode($json, true);
        }
        return (array) $data;
    }

}
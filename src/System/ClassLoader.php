<?php
/*
 * This file is part of the Salut library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\System;

/**
 * A simple class loader a la PSR-4
 *
 *
 */
final class ClassLoader
{

    /**
     * @var array
     */
    private $prefixMap = [];


    /**
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $prefix => $spec) {
            $dirList = (array) $spec;
            foreach ($dirList as $dir) {
                $prepend = false;
                if (substr($dir, 0, 1) == '^') {
                    $prepend = true;
                    $dir = ltrim(substr($dir, 1));
                }
                $this->add($prefix, $dir, $prepend);
            }
        }
    }

    /**
     * @return bool
     * @see spl_autoload_register()
     */
    public function register()
    {
        return spl_autoload_register([$this, 'load']);
    }

    /**
     * @return bool
     * @see spl_autoload_unregister()
     */
    public function unregister()
    {
        return spl_autoload_unregister([$this, 'load']);
    }

    /**
     * Auto-load interface function
     * @param string $class
     */
    public function load($class)
    {
        $path = $this->path($class);
        if ($path) {
            require $path;
        }
    }

    /**
     * Adds a new mapping rule
     * @param string $prefix
     * @param string $dir
     * @param string $prepend
     * @return self
     */
    public function add($prefix, $dir, $prepend = false)
    {
        $key = rtrim($prefix, '\\_');
        $value = rtrim($dir, DIRECTORY_SEPARATOR);
        if (!array_key_exists($key, $this->prefixMap)) {
            $this->prefixMap[$key] = [];
        }
        if ($prepend) {
            array_unshift($this->prefixMap[$key], $value);
        } else {
            array_push($this->prefixMap[$key], $value);
        }
        return $this;
    }

    /**
     * Returns a path to class file
     * @param string $class
     */
    public function path($class)
    {
        if (strpos($class, '\\') === false) {
            $path = $this->findPath($class, '_');
        } else {
            $path = $this->findPath($class, '\\');
        }
        return $path;
    }

    /**
     * @param string $class
     * @param string $separator
     * @return string|bool
     */
    private function findPath($class, $separator)
    {
        $path = false;
        $prefix = $class;
        while ($prefix) {
            if (isset($this->prefixMap[$prefix])) {
                $suffix = substr($class, strlen($prefix) + 1);
                $tail = str_replace($separator, DIRECTORY_SEPARATOR, $suffix);
                $path = $this->completePath($prefix, $tail);
            }
            if ($path) {
                break;
            }
            $prefix = $this->prefixPop($prefix, $separator);
        }
        return $path;
    }

    /**
     *
     */
    private function completePath($key, $tail)
    {
        $head = false;
        foreach ($this->prefixMap[$key] as $base) {
            $path = $base . DIRECTORY_SEPARATOR . $tail . ".php";
            if (file_exists($path)) {
                $head = $path;
                break;
            }
        }
        return $head;
    }

    /**
     * @param string $prefix
     * @param string $separator
     * @return string
     */
    private function prefixPop($prefix, $separator)
    {
        $pos = strrpos($prefix, $separator);
        if ($pos === false) {
            $prefix = "";
        } else {
            $prefix = substr($prefix, 0, $pos);
        }
        return $prefix;
    }

}

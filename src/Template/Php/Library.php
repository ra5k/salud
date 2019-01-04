<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Template\Php;

// [imports]
use Ra5k\Salud\Exception\RuntimeException;


/**
 *
 *
 *
 */
final class Library
{
    /**
     * The base directory
     * @var string
     */
    private $base;

    /**
     * @var bool
     */
    private $relative;

    /**
     * @param string $base
     * @param bool $relative
     */
    public function __construct(string $base, bool $relative = false)
    {
        if (substr($base, -1) != '/') {
            $base .= '/';
        }
        $this->base = $base;
        $this->relative = $relative;
    }

    /**
     * @param string $name
     * @param mixed $context
     * @return Script
     */
    public function script(string $name, $context): Script
    {
        return new Script($this, $name, Context::simple($context));
    }

    /**
     *
     * @param string $name
     * @param Script $referer
     * @return string
     */
    public function resolve(string $name, Script $referer = null): string
    {
        if ($referer && $this->relative) {
            $name = $this->join($referer->name(), $name);
        }
        return $this->reduce($name);
    }

    /**
     *
     * @param string $name
     * @param Script $referer
     * @return string
     */
    public function path(string $name): string
    {
        return $this->base . ltrim($name, '/');
    }

    /**
     * @param string $path
     * @return string
     */
    private function reduce(string $path)
    {
        $reduced = [];
        foreach (explode('/', $path) as $node) {
            if ($node == '.') {
                continue;
            } else if ($node == '..') {
                array_pop($reduced);
            } else {
                array_push($reduced, $node);
            }
        }
        return implode('/', $reduced);
    }

    /**
     * @param string $base
     * @param string $path
     * @return string
     */
    private function join(string $base, string $path)
    {
        $cut = strrpos($base, '/');
        if (substr($path, 0, 1) == '/') {
            $joined = ltrim($path, '/');
        } else if ($cut === false) {
            $joined = $path;
        } else {
            $tail = ($path) ? "/$path" : $path;
            $joined = substr($base, 0, $cut) . $tail;
        }
        return $joined;
    }

}

<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Sapi;

// [imports]
use Ra5k\Salud\Sapi;


/**
 *
 *
 *
 */
abstract class Cached implements Sapi
{
    /**
     * @var Sapi
     */
    private $origin;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $suffix;

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $target;

    /**
     * @param Sapi $origin
     */
    public function __construct(Sapi $origin)
    {
        $this->origin = $origin;
    }

    public function name(): string
    {
        if ($this->name === null) {
            $this->name = $this->origin->name();
        }
        return $this->name;
    }

    public function param(string $name)
    {
        return $this->origin->param($name);
    }

    public function path(): string
    {
        if ($this->path === null) {
            $this->path = $this->origin->path();
        }
        return $this->path;
    }

    public function prefix(): string
    {
        if ($this->prefix === null) {
            $this->prefix = $this->origin->prefix();
        }
        return $this->prefix;
    }

    public function root(): string
    {
        if ($this->root === null) {
            $this->root = $this->origin->root();
        }
        return $this->root;
    }

    public function suffix(): string
    {
        if ($this->suffix === null) {
            $this->suffix = $this->origin->suffix();
        }
        return $this->suffix;
    }

    public function target(): string
    {
        if ($this->target === null) {
            $this->target = $this->origin->target();
        }
        return $this->target;
    }

}

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
abstract class Wrap implements Sapi
{
    /**
     * @var Sapi
     */
    private $origin;

    /**
     * @param Sapi $origin
     */
    public function __construct(Sapi $origin)
    {
        $this->origin = $origin;
    }

    public function name(): string
    {
        return $this->origin->name();
    }

    public function param(string $name)
    {
        return $this->origin->param($name);
    }

    public function path(): string
    {
        return $this->origin->path();
    }

    public function prefix(): string
    {
        return $this->origin->prefix();
    }

    public function root(): string
    {
        return $this->origin->root();
    }

    public function suffix(): string
    {
        return $this->origin->suffix();
    }

    public function target(): string
    {
        return $this->origin->target();
    }

}

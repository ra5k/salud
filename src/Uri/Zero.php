<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Uri;

// [imports]
use Ra5k\Salud\Uri;

/**
 *
 *
 *
 */
final class Zero implements Uri
{

    public function __toString(): string
    {
        return $this->toString();
    }

    public function authority(): string
    {
        return '';
    }

    public function fragment(): string
    {
        return '';
    }

    public function host(): string
    {
        return '';
    }

    public function isAbsolute(): bool
    {
        return false;
    }

    public function isOpaque(): bool
    {
        return false;
    }

    public function normalize(): Uri
    {
        return $this;
    }

    public function pass(): string
    {
        return '';
    }

    public function path(): string
    {
        return '';
    }

    public function port(): int
    {
        return -1;
    }

    public function query(): string
    {
        return '';
    }

    public function relativize($ref): Uri
    {
        return $this;
    }

    public function resolve($ref): Uri
    {
        return $this;
    }

    public function scheme(): string
    {
        return '';
    }

    public function toString(): string
    {
        return '';
    }

    public function user(): string
    {
        return '';
    }

}

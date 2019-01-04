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
abstract class Wrap implements Uri
{
    /**
     * @var Uri
     */
    private $orig;

    /**
     * @param Uri $uri
     */
    public function __construct(Uri $uri)
    {
        $this->orig = $uri;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function authority(): string
    {
        return $this->orig->authority();
    }

    public function fragment(): string
    {
        return $this->orig->fragment();
    }

    public function host(): string
    {
        return $this->orig->host();
    }

    public function isAbsolute(): bool
    {
        return $this->orig->isAbsolute();
    }

    public function isOpaque(): bool
    {
        return $this->orig->isOpaque();
    }

    public function normalize(): Uri
    {
        return $this->orig->normalize();
    }

    public function pass(): string
    {
        return $this->orig->pass();
    }

    public function path(): string
    {
        return $this->orig->path();
    }

    public function port(): int
    {
        return $this->orig->port();
    }

    public function query(): string
    {
        return $this->orig->query();
    }

    public function relativize($ref): Uri
    {
        return $this->orig->relativize($ref);
    }

    public function resolve($ref): Uri
    {
        return $this->orig->resolve($ref);
    }

    public function scheme(): string
    {
        return $this->orig->scheme();
    }

    public function toString(): string
    {
        $view = new View($this);
        return $view->uri();
    }

    public function user(): string
    {
        return $this->orig->user();
    }

}

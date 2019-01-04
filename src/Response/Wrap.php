<?php
/*
 * This file is part of the Salud library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Response;

// [imports]
use Ra5k\Salud\Response;

/**
 *
 *
 *
 */
abstract class Wrap implements Response
{
    /**
     * @var Response
     */
    private $origin;

    /**
     * @param Response $origin
     */
    public function __construct(Response $origin)
    {
        $this->origin = $origin;
    }

    public function cookies(): array
    {
        return $this->origin->cookies();
    }

    public function headers(): array
    {
        return $this->origin->headers();
    }

    public function status(): Status
    {
        return $this->origin->status();
    }

    /**
     * Sends the response
     */
    public function write(): bool
    {
        return $this->origin->write();
    }

}

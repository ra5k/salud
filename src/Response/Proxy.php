<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
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
final class Proxy implements Response
{
    // [traits]
    use Defaults;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     *
     */
    public function write(): bool
    {
        $function = $this->callback;
        $function();
        return true;
    }

}

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
final class WithBody extends Wrap
{
    /**
     * @var string
     */
    private $body;

    /**
     * @param Response $origin
     * @param string $body
     */
    public function __construct(Response $origin, string $body)
    {
        parent::__construct($origin);
        $this->body = $body;
    }

    /**
     * @writes the body to the output stream
     */
    public function write(): bool
    {
        echo $this->body;
        return true;
    }

}

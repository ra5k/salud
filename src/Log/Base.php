<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Log;

// [imports]
use Ra5k\Salud\Log;
use Psr\Log\AbstractLogger;

/**
 *
 *
 *
 */
abstract class Base extends AbstractLogger implements Log
{

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct(string $token = null)
    {
        $this->token = ($token === null) ? substr(md5(mt_rand() + time()), 0, 6) : $token;
    }

    /**
     * @return string
     */
    public function token(): string
    {
        return $this->token;
    }

}

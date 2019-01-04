<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Indicator;

// [imports]
use Ra5k\Salud\{Indicator, Response, Exception\BadMethodCallException};

/**
 *
 *
 *
 */
final class None implements Indicator
{

    /**
     * @return bool
     */
    public function isMatch(): bool
    {
        return false;
    }

    /**
     * @return Response
     * @throws BadMethodCallException
     */
    public function response(): Response
    {
        throw new BadMethodCallException("A dummy fork target has no response");
    }

}

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
use Ra5k\Salud\{Indicator, Response};

/**
 *
 *
 *
 */
final class Instant implements Indicator
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function isMatch(): bool
    {
        return true;
    }

    /**
     * @return Response
     */
    public function response(): Response
    {
        return $this->response;
    }

}

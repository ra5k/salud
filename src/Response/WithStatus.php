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
final class WithStatus extends Wrap
{
    /**
     * @var Status
     */
    private $status;

    /**
     * @param Response $origin
     * @param Status|int $status
     */
    public function __construct(Response $origin, $status)
    {
        parent::__construct($origin);
        $this->status = ($status instanceof Status) ? $status : new Status($status);
    }

    /**
     * @return Status
     */
    public function status(): Status
    {
        return $this->status;
    }

}

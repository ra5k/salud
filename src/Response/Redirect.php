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
 * Sends a HTML string
 *
 */
final class Redirect implements Response
{
    // [traits]
    use Defaults;

    /**
     * @var string
     */
    private $location;

    /**
     * @var Status
     */
    private $status;

    /**
     * @param string $location
     * @param Status|int $status
     */
    public function __construct(string $location, $status = 303)
    {
        $this->location = $location;
        $this->status = ($status instanceof Status) ? $status : new Status($status);
    }

    /**
     * @return Status
     */
    public function status(): Status
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function headers(): array
    {
        return [
            new Header('Location', $this->location)
        ];
    }

    /**
     * @return bool
     */
    public function write(): bool
    {
        return true;
    }

}

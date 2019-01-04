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
use Ra5k\Salud\{Uri};

/**
 *
 *
 *
 */
final class WithHost extends Wrap
{
    /**
     * @var string
     */
    private $host;

    /**
     * @param Uri $uri
     * @param string $host
     */
    public function __construct(Uri $uri, string $host)
    {
        parent::__construct($uri);
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function host(): string
    {
        return $this->host;
    }

}

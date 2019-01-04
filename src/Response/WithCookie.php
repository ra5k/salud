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
final class WithCookie extends Wrap
{
    /**
     * @var Cookie
     */
    private $cookie;

    /**
     * @param Response $origin
     * @param Cookie $cookie
     */
    public function __construct(Response $origin, Cookie $cookie)
    {
        parent::__construct($origin);
        $this->cookie = $cookie;
    }

    /**
     * @return array
     */
    public function cookies(): array
    {
        $map = parent::cookies();
        $cookie = $this->cookie;
        $map[$cookie->name()] = $cookie;
        return $map;
    }

}

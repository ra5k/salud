<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Request;


// [imports]
use Ra5k\Salud\{Request, Uri};

/**
 *
 *
 *
 */
final class WithUri extends Wrap
{
    /**
     * @var Uri
     */
    private $uri;

    /**
     * @var Uri\Query
     */
    private $query;

    /**
     * @param Request $origin
     * @param Uri|string $uri
     */
    public function __construct(Request $origin, $uri)
    {
        parent::__construct($origin);
        $this->uri = ($uri instanceof Uri) ? $uri : new Uri\Std($uri);
    }

    /**
     * @return Uri
     */
    public function uri(): Uri
    {
        return $this->uri;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        if (null === $this->query) {
            $this->query = new Uri\Query($this->uri->query());
        }
        return $this->query->param($name);
    }

}

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
use Ra5k\Salud\Uri;


/**
 * Renders an URI from its components
 *
 *
 */
final class View
{
    /**
     * The source
     * @var Uri
     */
    private $src;

    /**
     * @param Uri $src  The source
     */
    public function __construct(Uri $src)
    {
        $this->src = $src;
    }

    /**
     * String-cast operator
     * @return string
     */
    public function __toString()
    {
        return $this->uri();
    }

    /**
     * @param Uri $src
     * @return string
     */
    public function __invoke(Uri $src)
    {
        $view = new self($src);
        return $view->uri();
    }

    /**
     * Renders the whole URI
     * @return string
     */
    public function uri()
    {
        $src = $this->src;
        $uri = '';
        //
        $scheme = $src->scheme();
        if ($scheme) {
            $uri .= "{$scheme}:";
        }
        $authority = $this->authority();
        if ($authority) {
            $uri .= "//{$authority}";
        }
        $path = $src->path();
        if ($path) {
            $uri .= $path;
        }
        $query = $src->query();
        if ($query) {
            $uri .= "?{$query}";
        }
        $fragment = $src->fragment();
        if ($fragment) {
            $uri .= "#{$fragment}";
        }
        //
        return $uri;
    }

    /**
     * Renders the authority-part of an URI
     * @return string
     */
    public function authority()
    {
        $src = $this->src;
        $user = $src->user();
        $pass = $src->pass();
        $host = $src->host();
        $port = $src->port();

        if ($user || $pass) {
            $auth = "{$user}:{$pass}@{$host}";
        } else {
            $auth = $host;
        }
        if ($port && $port >= 0) {
            $auth .= ":{$port}";
        }

        return $auth;
    }

}

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
use Ra5k\Salud\{Request, Input, Uri};


/**
 *
 *
 *
 */
final class Instant implements Request
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var Uri
     */
    private $uri;

    /**
     * @var Uri\Query
     */
    private $query;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $post;

    /**
     * @param Uri|string $uri
     * @param string $body
     */
    function __construct($uri, string $body = '', string $method = 'GET')
    {
        $this->uri = ($uri instanceof Uri) ? $uri : new Uri\Std($uri);
        $this->body = $body;
        $this->method = strtoupper($method);
    }

    public function attribute($name)
    {
        return null;
    }

    public function body(): Input\Forward
    {
        return new Input\Buffer($this->body);
    }

    public function cookie($name)
    {
        return null;
    }

    public function get($name)
    {
        return $this->query()->param($name);
    }

    public function header($name)
    {
        return null;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function post($name)
    {
        if ($this->post === null && $this->method === 'POST') {
            $this->post = parse_url($this->body);
        }
        return isset($this->post[$name]) ? $this->post[$name] : null;
    }

    public function protocol(): string
    {
        return 'HTTP/1.1';
    }

    public function uri(): Uri
    {
        return $this->uri;
    }

    public function uploads(): array
    {
        return [];
    }

    /**
     * @return Uri\Query
     */
    private function query()
    {
        if ($this->query === null) {
            $this->query = new Uri\Query($this->uri->query());
        }
        return $this->query;
    }

}

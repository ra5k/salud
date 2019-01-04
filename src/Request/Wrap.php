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
 * Description of Wrap
 *
 *
 */
abstract class Wrap implements Request
{
    /**
     * @var Request
     */
    private $origin;
    
    /**
     * @param Request $origin
     */
    public function __construct(Request $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function attribute($name)
    {
        return $this->origin->attribute($name);
    }

    public function body(): Input\Forward
    {
        return $this->origin->body();
    }

    public function cookie($name): string
    {
        return $this->origin->cookie($name);
    }

    public function get($name)
    {
        return $this->origin->get($name);
    }

    public function header($name): string
    {
        return $this->origin->header($name);
    }

    public function method(): string
    {
        return $this->origin->method();
    }

    public function post($name)
    {
        return $this->origin->post($name);
    }

    public function protocol(): string
    {
        return $this->origin->protocol();
    }

    public function uploads(): array
    {
        return $this->origin->uploads();
    }

    public function uri(): Uri
    {
        return $this->origin->uri();
    }

}

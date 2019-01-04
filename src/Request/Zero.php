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
use Ra5k\Salud\{Request, Uri, Input};

/**
 *
 *
 *
 */
final class Zero implements Request
{
    
    public function attribute($name)
    {
        return null;
    }

    public function body(): Input\Forward
    {
        return new Input\Zero;
    }

    public function cookie($name): string
    {
        return '';
    }

    public function get($name)
    {
        return null;
    }

    public function header($name)
    {
        return null;
    }

    public function method(): string
    {
        return '';
    }

    public function post($name)
    {
        return null;
    }

    public function protocol(): string
    {
        return '';
    }

    public function uploads(): array
    {
        return [];
    }

    public function uri(): Uri
    {
        return new Uri\Zero;
    }

}

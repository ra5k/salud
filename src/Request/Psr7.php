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
use Psr\Http\Message\ServerRequestInterface;

/**
 *
 *
 * @link https://www.php-fig.org/psr/psr-7/
 */
final class Psr7 implements Request
{
    /**
     * @var ServerRequestInterface
     */
    private $origin;
    
    /**
     * @param ServerRequestInterface $origin
     */
    public function __construct(ServerRequestInterface $origin)
    {
        $this->origin = $origin;
    }

    public function attribute($name)
    {
        
    }

    public function body(): Input\Forward
    {
        
    }

    public function cookie($name): string
    {
        
    }

    public function get($name)
    {
        
    }

    public function header($name): string
    {
        
    }

    public function method(): string
    {
        
    }

    public function post($name)
    {
        
    }

    public function protocol(): string
    {
        
    }

    public function uploads(): array
    {
        
    }

    public function uri(): Uri
    {
        
    }

}

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
use Psr\Http\Message\{ServerRequestInterface, UploadedFileInterface, UriInterface};


/**
 * TODO: Complete
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

    /**
     * @param string $name
     * @return mixed
     */
    public function attribute($name)
    {
        return $this->origin->getAttribute($name);
    }

    /**
     * 
     * @return Input\Forward
     */
    public function body(): Input\Forward
    {
        return new Input\File('php://input', 'r');
    }

    /**
     * @param string $name
     * @return mixes
     */
    public function cookie($name): string
    {
        $cookies = $this->origin->getCookieParams();
        return $cookies[$name] ?? null;
    }

    /**
     * 
     * @param string $name
     */
    public function get($name)
    {
        $params = $this->origin->getQueryParams();
        return $params[$name] ?? null;
    }

    /**
     * @param string $name
     * @return string
     */
    public function header($name)
    {
        return $this->origin->getHeader($name);
    }

    /**
     * 
     * @return string
     */
    public function method(): string
    {
        return (string) $this->origin->getMethod();
    }

    /**
     * 
     * @param string $name
     */
    public function post($name)
    {
        $body = $this->origin->getParsedBody();
        return $body[$name] ?? null;
    }

    /**
     * 
     * @return string
     */
    public function protocol(): string
    {
        $v = $this->origin->getProtocolVersion();
        return "HTTP/$v";
    }

    /**
     * 
     * @return array
     */
    public function uploads(): array
    {
        $files = $this->origin->getUploadedFiles();
    }

    /**
     * 
     * @return Uri
     */
    public function uri(): Uri
    {
        $u = $this->origin->getUri();
        return new Uri\Std((string) $u);
    }

}

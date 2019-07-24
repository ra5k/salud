<?php
/*
 * This file is part of the Salud library
 * (c) 2019 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Request;

// [imports]
use Ra5k\Salud\{Request, Input, Uri, Upload, Sapi};


/**
 * Fetches request information via PHP's filter_input() function.
 * This method is safer than using super globals like $_GET, $_POST, $_COOKIE, ...
 *
 */
final class Solid implements Request
{
    /**
     * @var Sapi
     */
    private $sapi;
    
    /**
     * @var Uri
     */
    private $uri;
    
    /**
     * @var array
     */
    private $uploads;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param Sapi $sapi
     * @param array $attributes
     */
    public function __construct(Sapi $sapi = null, array $attributes = [])
    {
        $this->sapi = $sapi ?? new Sapi\Auto();
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return (string) $this->srv('REQUEST_METHOD');
    }

    /**
     * @return string
     */
    public function protocol(): string
    {
        return (string) $this->srv('SERVER_PROTOCOL');
    }

    /**
     * @return Uri
     */
    public function uri(): Uri
    {
        if ($this->uri === null) {
            $scheme = $this->srv('REQUEST_SCHEME') ?: 'http';
            $host = $this->srv('HTTP_HOST');
            list ($path, $query) = $this->splitPath($this->srv('REQUEST_URI'));
            $this->uri = new Uri\Std([
                'scheme' => $scheme, 'host' => $host, 'path' => $path, 'query' => $query
            ]);
        }
        return $this->uri;
    }

    /**
     * @return Input\Forward
     */
    public function body(): Input\Forward
    {
        return new Input\File('php://input', 'r');
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        $value = filter_input(INPUT_GET, $name, FILTER_DEFAULT, FILTER_REQUIRE_SCALAR);
        if ($value === false) {
            $value = filter_input(INPUT_GET, $name, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        }
        return $value;
    }

    /**
     * @param string $name
     * @return string
     */
    public function header($name)
    {
        return $this->srv('HTTP_' . $this->cgiName($name));
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function post($name)
    {
        $value = filter_input(INPUT_POST, $name, FILTER_DEFAULT, FILTER_REQUIRE_SCALAR);
        if ($value === false) {
            $value = filter_input(INPUT_POST, $name, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        }
        return $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function cookie($name)
    {
        return filter_input(INPUT_COOKIE, $name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function attribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    /**
     * @return array
     */
    public function uploads(): array
    {
        if ($this->uploads === null) {
            if (isset($_FILES) && is_array($_FILES)) {
                $this->uploads = Upload\Files::tree($_FILES);
            } else {
                $this->uploads = [];
            }
        }
        return $this->uploads;
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function srv(string $name)
    {
        return $this->sapi->param($name);
    }
    
    /**
     * @param string $name
     * @return string
     */
    private function cgiName($name)
    {
        $upper = strtoupper($name);
        return str_replace('-', '_', $upper);
    }

    /**
     * @param string $tail
     * @return string
     */
    private function splitPath($tail)
    {
        $parts = explode('?', $tail, 2);
        if (count($parts) < 2) {
            $parts[] = '';
        }
        return $parts;
    }
    
}

<?php
/*
 * This file is part of the Salut library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Request;

// [imports]
use Ra5k\Salud\{Request, Input, Uri, Upload};


/**
 * Fetches request information via PHP's filter_input() function
 *
 */
final class Sapi implements Request
{
    /**
     * @var array
     */
    private $uploads;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return (string) filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }
    
    /**
     * @return string
     */
    public function protocol(): string
    {
        return (string) filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    }

    /**
     * @return Uri
     */
    public function uri(): Uri
    {
        return new Uri\Std(filter_input(INPUT_SERVER, 'REQUEST_URI'));
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
        return filter_input(INPUT_SERVER, 'HTTP_' . $this->cgiName($name));
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
                $this->uploads = Upload\Sapi::tree($_FILES);
            } else {
                $this->uploads = [];
            }
        }
        return $this->uploads;
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

}

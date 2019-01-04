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
 *
 *
 *
 */
abstract class Dynamic implements Uri
{
    /**
     * @var array
     */
    private static $keys = [
        'scheme' => 's',
        'user' => 's',
        'pass' => 's',
        'host' => 's',
        'port' => 'i',
        'path' => 's',
        'query' => 's',
        'fragment' => 's'
    ];

    /**
     * Parts of the Uri as returned by the Parser or parse_url()
     * @var array
     */
    private $parts;

    /**
     * String-version of the URI
     * @var string
     */
    private $uri;

    /**
     * @return string
     */
    public function scheme(): string
    {
        return $this->parsed()->part('scheme', '');
    }

    /**
     * @return string
     */
    public function user(): string
    {
        return $this->parsed()->part('user', '');
    }

    /**
     * @return string
     */
    public function pass(): string
    {
        return $this->parsed()->part('pass', '');
    }

    /**
     * @return string
     */
    public function host(): string
    {
        return $this->parsed()->part('host', '');
    }

    /**
     * @return int
     */
    public function port(): int
    {
        return $this->parsed()->part('port', -1);
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return $this->parsed()->part('path', '');
    }

    /**
     * @return string
     */
    public function query(): string
    {
        return $this->parsed()->part('query', '');
    }

    /**
     * @return string
     */
    public function fragment(): string
    {
        return $this->parsed()->part('fragment', '');
    }

    /**
     * @return string
     */
    public function authority(): string
    {
        $view = new View($this);
        return $view->authority();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        if (null === $this->uri) {
            $view = new View($this);
            $this->uri = $view->uri();
        }
        return $this->uri;
    }

    /**
     * Make sure that the URI has been parsed
     * @return static
     */
    protected final function parsed()
    {
        if ($this->parts === null) {
            if ($this->uri) {
                $parts = (new Parser($this->uri))->parse();
                $this->hydrate($parts);
            } else {
                $this->parts = ['path' => ''];
            }
        }
        return $this;
    }

    /**
     * @param array $input
     * @return array
     */
    protected function hydrate(array $input)
    {
        if ($this->parts === null) {
            $this->parts = [];
        }
        foreach ($input as $key => $value) {
            if (!isset(self::$keys[$key])) {
                continue;
            }
            // Update|Delete
            if ($value === null) {
                unset($this->parts[$key]);
            } else {
                $this->parts[$key] = $this->typeCast(self::$keys[$key], $value);
            }
            // Trigger rebuild
            $this->uri = null;
        }
        return $this->parts;
    }

    /**
     * @param string $uri
     * @return static
     */
    protected function reverse($uri)
    {
        $this->uri = ($uri === null) ? null : (string) $uri;
        $this->parts = null;
        return $this;
    }


    /**
     * @param string $name
     * @return string|null
     */
    private function part($name, $default = null)
    {
        return $this->parts[$name] ?? $default;
    }

    /**
     * PHP type-cast helper
     *
     * @param string $type
     * @param mixed $value
     * @return mixed
     */
    private function typeCast($type, $value)
    {
        switch ($type) {
            case 'i': $value = (int) $value; break;
            case 'f': $value = (float) $value; break;
            case 'b': $value = (bool) $value; break;
            case 's': $value = (string) $value; break;
            case 'a': $value = (array) $value; break;
            case 'o': $value = (object) $value; break;
        }
        return $value;
    }

}

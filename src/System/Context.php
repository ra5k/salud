<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\System;


/**
 *
 *
 */
final class Context
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $suffix;

    /**
     * Indicate whether we have to fall back to super global access
     *
     * @var bool
     * @note Due to a bug, filter_input will not work on INPUT_SERVER in the FastCGI API
     */
    private static $fallback;

    /**
     * Contents of the $_SERVER array
     * @var array
     */
    private static $server;

    /**
     *
     */
    public function __construct()
    {
        if (self::$server === null) {
            if (PHP_SAPI == 'cgi-fcgi') {
                self::$server = (array) $_SERVER;
                self::$fallback = true;
            } else {
                self::$server = [];
                self::$fallback = false;
            }
        }
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        $filename = $this->root() . $this->path();
        return is_file($filename) || is_link($filename);
    }

    /**
     * Returns the document root
     * @return string
     */
    public function root()
    {
        return $this->server('DOCUMENT_ROOT');
    }

    /**
     * Returns the current request path
     * @return string
     */
    public function path()
    {
        if ($this->path === null) {
            $url = $this->server('REQUEST_URI');
            $query = $this->server('QUERY_STRING');
            if (substr($url, -strlen($query)) == $query) {
                $path = rtrim(substr($url, 0, -strlen($query)), '?');
            } else {
                $path = parse_url($url, PHP_URL_PATH);
            }
            $this->path = $path;
        }
        return $this->path;
    }


    /**
     * Returns the base path of this application
     * @return string
     */
    public function prefix()
    {
        if ($this->prefix === null) {
            $script = $this->server('SCRIPT_NAME');
            $request = $this->server('REQUEST_URI');
            //
            if (PHP_SAPI == 'cli-server') {
                $prefix = '';
            } else if (substr($request, 0, strlen($script)) == $script) {
                $prefix = $script;
            } else {
                $offset = strrpos($script, '/');
                if ($offset !== false) {
                    $prefix = substr($script, 0, $offset);
                } else {
                    $prefix = '';
                }
            }
            $this->prefix = (string) $prefix;
        }
        return $this->prefix;
    }

    /**
     * Returns the relative path (to the base path) of the current request
     * @return string
     */
    public function suffix()
    {
        if ($this->suffix === null) {
            $this->suffix = (string) substr($this->path(), strlen($this->prefix()));
        }
        return $this->suffix;
    }

    /**
     * @param string $variable
     * @return mixed
     */
    private function server(string $variable)
    {
        if (self::$fallback) {
            $value = self::$server[$variable] ?? null;
        } else {
            $value = filter_input(INPUT_SERVER, $variable);
        }
        return $value;
    }

}

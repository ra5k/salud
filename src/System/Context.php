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
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    }

    /**
     * Returns the current request path
     * @return string
     */
    public function path()
    {
        if ($this->path === null) {
            $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
            $query = filter_input(INPUT_SERVER, 'QUERY_STRING');
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
            $script = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
            $request = filter_input(INPUT_SERVER, 'REQUEST_URI');
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

}

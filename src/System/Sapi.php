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
 * Pulls basic information from the server API
 *
 *
 */
final class Sapi
{
    /**
     * @var string
     */
    private static $path;

    /**
     * @var string
     */
    private static $prefix;

    /**
     * @var string
     */
    private static $suffix;

    /**
     * @var string
     */
    private static $script;

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
    public static function init()
    {
        if (PHP_SAPI == 'cgi-fcgi') {
            global $_SERVER;
            self::$server = (array) $_SERVER;
            self::$fallback = true;
        } else {
            self::$server = [];
            self::$fallback = false;
        }
    }

    /**
     * @return string
     */
    public static function name()
    {
        return PHP_SAPI;
    }

    /**
     * @return string
     */
    public static function script(): string
    {
        if (self::$script === null) {
            self::$script = self::root() . self::path();
        }
        return self::$script;
    }

    /**
     * Returns the document root
     * @return string
     */
    public static function root(): string
    {
        return self::server('DOCUMENT_ROOT');
    }

    /**
     * Returns the current request path
     * @return string
     */
    public static function path(): string
    {
        if (self::$path === null) {
            $url = self::server('REQUEST_URI');
            $query = self::server('QUERY_STRING');
            if (substr($url, -strlen($query)) == $query) {
                $path = rtrim(substr($url, 0, -strlen($query)), '?');
            } else {
                $path = parse_url($url, PHP_URL_PATH);
            }
            self::$path = $path;
        }
        return self::$path;
    }

    /**
     * Returns the base path of this application
     * @return string
     */
    public static function prefix()
    {
        if (self::$prefix === null) {
            $script = self::server('SCRIPT_NAME');
            $request = self::server('REQUEST_URI');
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
            self::$prefix = (string) $prefix;
        }
        return self::$prefix;
    }

    /**
     * Returns the relative path (to the base path) of the current request
     * @return string
     */
    public static function suffix()
    {
        if (self::$suffix === null) {
            self::$suffix = (string) substr(self::path(), strlen(self::prefix()));
        }
        return self::$suffix;
    }

    /**
     * @param string $variable
     * @return mixed
     */
    public static function server(string $variable)
    {
        if (self::$server === null) {
            self::init();
        }
        if (self::$fallback) {
            $value = self::$server[$variable] ?? null;
        } else {
            $value = filter_input(INPUT_SERVER, $variable);
        }
        return $value;
    }

}

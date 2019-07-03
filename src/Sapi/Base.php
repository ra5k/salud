<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Sapi;

// [imports]
use Ra5k\Salud\Sapi;


/**
 *
 *
 *
 */
abstract class Base implements Sapi
{

    /**
     * @return string
     */
    public function name(): string
    {
        return PHP_SAPI;
    }

    /**
     * @return string
     */
    public function path(): string
    {
        $url = $this->param('REQUEST_URI');
        $query = $this->param('QUERY_STRING');
        if (substr($url, -strlen($query)) == $query) {
            $path = rtrim(substr($url, 0, -strlen($query)), '?');
        } else {
            $path = parse_url($url, PHP_URL_PATH);
        }
        return $path;
    }

    /**
     * @return string
     */
    public function prefix(): string
    {
        $script = $this->param('SCRIPT_NAME');
        $request = $this->param('REQUEST_URI');
        //
        if (substr($request, 0, strlen($script)) == $script) {
            $prefix = $script;
        } else {
            $offset = strrpos($script, '/');
            if ($offset !== false) {
                $prefix = substr($script, 0, $offset);
            } else {
                $prefix = '';
            }
        }
        return (string) $prefix;
    }

    /**
     * @return string
     */
    public function root(): string
    {
        return $this->param('DOCUMENT_ROOT') ?? '';
    }

    /**
     * @return string
     */
    public function suffix(): string
    {
        return (string) substr($this->path(), strlen($this->prefix()));
    }

    /**
     * @return string
     */
    public function target(): string
    {
        return $this->root() . $this->path();
    }

}

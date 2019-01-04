<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud;

/**
 *
 *
 */
interface Request
{
    /**
     * GET,POST,PUT,DELETE
     * @return string
     */
    public function method(): string;

    /**
     * E.g: HTTP/1.1
     * @return string
     */
    public function protocol(): string;

    /**
     * Returns the request URI
     * @return Uri
     */
    public function uri(): Uri;

    /**
     * @param string $name
     * @return string
     */
    public function header($name);

    /**
     * Returns the of a GET parameter
     * @param string $name
     * @return mixed
     */
    public function get($name);

    /**
     * Returns the of a POST parameter
     * @param string $name
     * @return mixed
     */
    public function post($name);

    /**
     * Returns the value of a cookie
     * @param string $name
     * @return string
     */
    public function cookie($name);

    /**
     * Returns the file upload tree
     * @return array
     */
    public function uploads(): array;

    /**
     * @return Input\Forward
     */
    public function body(): Input\Forward;

    /**
     * User-land parameter access
     * @return mixed
     */
    public function attribute($name);

}

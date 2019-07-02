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
 * A basic SAPI (Server Application Programming Interface)
 *
 */
interface Sapi
{

    /**
     * @return string
     */
    public function name(): string;

    /**
     * Returns the value of a SAPI parameter (variable)
     *
     * @param string $name
     * @return mixed
     */
    public function param(string $name);

    /**
     * Returns the request path
     * @return string
     */
    public function path(): string;

    /**
     *
     * @return string
     */
    public function prefix(): string;

    /**
     *
     * @return string
     */
    public function suffix(): string;

    /**
     * @return string
     */
    public function target(): string;

    /**
     * Returns the document root
     * @return string
     */
    public function root(): string;

}

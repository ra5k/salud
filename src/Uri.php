<?php
/*
 * This file is part of the Salus library
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
interface Uri
{

    /**
     * String-cast operator
     * @return string
     */
    public function __toString(): string;

    /**
     * @return string
     */
    public function toString(): string;

    /**
     * The scheme
     * @return string
     */
    public function scheme(): string;

    /**
     * [user:pass@]host[:port]
     * @return string
     */
    public function authority(): string;

    /**
     * Returns the user part of the authority
     * @return string
     */
    public function user(): string;

    /**
     * Returns the password part of the authority
     * @return string
     */
    public function pass(): string;

    /**
     * Return the host name
     * @return string
     */
    public function host(): string;

    /**
     * Returns the port number
     * @return int
     */
    public function port(): int;

    /**
     * Returns the path
     * @return string
     */
    public function path(): string;

    /**
     * Returns the query string
     * @return string
     */
    public function query(): string;

    /**
     * Returns  the fragment of the Uri
     * @return string
     */
    public function fragment(): string;

    /**
     * Resolves this Uri against $ref and returns (new) Uri object
     * @param string|Uri $ref
     * @return Uri
     */
    public function resolve($ref): Uri;

    /**
     * Resolves this Uri against $ref and returns (new) Uri object
     * @param string|Uri $ref
     * @return Uri
     */
    public function relativize($ref): Uri;

    /**
     * Normalize the path of this Uri
     * @return Uri
     */
    public function normalize(): Uri;

    /**
     * @return bool
     */
    public function isAbsolute(): bool;

    /**
     * @return bool
     */
    public function isOpaque(): bool;

}

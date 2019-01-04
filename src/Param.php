<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud;

// [imports]
use Iterator;


/**
 * A parameter node
 *
 */
interface Param
{
    /**
     * Returns the encapsulated data object
     * @return mixed
     */
    public function data();

    /**
     * Indicates whether this node represents an existing entity
     * @return bool
     */
    public function exists(): bool;

    /**
     * Creates a child node
     * @param mixed $data
     * @param bool $exists
     * @return Param
     */
    public function child($data, bool $exists = true): self;

    /**
     * Retrieves a (descendant) node
     * @param string|array $key
     * @return Param
     */
    public function node($key): self;

    /**
     * @param string|array $key
     * @param mixed $default
     * @return mixed
     */
    public function value($key, $default = null);

    /**
     * Iterates through the child nodes
     *
     * @return Iterator
     */
    public function items(): Iterator;

}

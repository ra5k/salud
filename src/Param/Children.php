<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Param;

use Ra5k\Salud\Param;
use Iterator;

/**
 * An on-demand child node iterator
 *
 *
 */
final class Children implements Iterator
{
    /**
     * @var Param
     */
    private $parent;

    /**
     * @var Iterator
     */
    private $iterator;


    /**
     * @param Param $parent
     * @param Iterator $iterator
     */
    public function __construct(Param $parent, Iterator $iterator)
    {
        $this->parent = $parent;
        $this->iterator = $iterator;
    }

    /**
     * @return Iterator
     */
    public function origin()
    {
        return $this->iterator;
    }

    /**
     * @return StdNode
     */
    public function current()
    {
        return $this->parent->child($this->iterator->current());
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     *
     */
    public function next()
    {
        return $this->iterator->next();
    }

    /**
     *
     */
    public function rewind()
    {
        return $this->iterator->rewind();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

}

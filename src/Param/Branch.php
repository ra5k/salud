<?php

/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Param;

// [imports]
use Iterator,
    ArrayIterator,
    IteratorAggregate,
    EmptyIterator;

/**
 *
 *
 */
trait Branch
{
    
    /**
     * Returns a child iterator
     * @return Iterator
     */
    private function childNodes($data)
    {
        if (is_array($data)) {
            $iterator = new ArrayIterator($data);
        } elseif ($data instanceof Iterator) {
            $iterator = $data;
        } elseif ($data instanceof IteratorAggregate) {
            $iterator = $data->getIterator();
        } else {
            $iterator = new EmptyIterator();
        }
        return new Children($this, $iterator);
    }
     
    
}

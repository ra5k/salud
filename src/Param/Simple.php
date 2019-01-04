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
use Ra5k\Salud\Param;
use Iterator;


/**
 * The standard node
 *
 *
 */
final class Simple implements Param
{
    // [imports]
    use Extraction, Branch;
    
    /**
     * @var mixed
     */
    private $data;

    /**
     * Indicates that this node represents an existing value
     * @var bool
     */
    private $exists = true;

    /**
     * @var string
     */
    private $separator = '.';

    /**
     *
     * @param mixed $data
     * @param bool $exists
     */
    public function __construct($data, $exists = null, $separator = null)
    {
        if ($data instanceof Param) {
            // Copy constructor:
            $this->data = $data->data();
            $this->exists = ($exists === null) ? $data->exists() : (bool) $exists;
            if ($data instanceof self) {
                $this->separator = ($separator === null) ? $data->separator() : (string) $separator;
            } else if ($separator !== null) {
                $this->separator = (string) $separator;
            }
        } else {
            // Standard constructor:
            $this->data = $data;
            if ($exists !== null) {
                $this->exists = (bool) $exists;
            }
            if ($separator !== null) {
                $this->separator = (string) $separator;
            }
        }
    }

    /**
     * @param mixed $data
     * @param bool $exists
     * @return self
     */
    public function child($data, bool $exists = true): Param
    {
        $child = new self($data, $exists);
        $child->separator = $this->separator;
        return $child;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param string|array $key
     * @return Param
     */
    public function node($key): Param
    {
        $data = null;
        $found = false;
        $path = $this->keyPath($key, $this->separator());
        $source = ($this->exists ? $this : false);
        //
        if ($source) {
            $data = $source->data;
            if (empty($path)) {
                $found = true;
            } else {
                $found = $this->pathValue($path, $data);
            }
        }
        //
        return $this->child($data, $found);
    }

    /**
     * Convenience function
     * @param string|array $key
     * @param mixed $default
     * @return mixed
     */
    public function value($key, $default = null)
    {
        $node = $this->node($key);
        return $node->exists() ? $node->data() : $default;
    }

    /**
     * Returns a child iterator
     * @return Iterator
     */
    public function items(): Iterator
    {
        return $this->childNodes($this->data());
    }

    /**
     * @return string
     */
    public function separator(): string
    {
        return $this->separator;
    }

}

<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Input;

// [import]
use SplFileObject;

/**
 *
 *
 *
 */
final class Buffer implements Forward, Random
{
    /**
     * @var string
     */
    private $buffer;
    
    /**
     * @var int
     */
    private $offset;
        
    /**
     * @var bool
     */
    private $valid;
    
    /**
     * @var string
     */
    private $data;
    
    /**
     * @param string $buffer
     * @param string $mode
     */
    public function __construct(string $buffer)
    {
        $this->buffer = $buffer;
        $this->offset = 0;
        $this->data = '';
        $this->valid = true;
    }

    /**
     * @return string
     */
    public function data(): string
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $length
     * @return Forward
     */
    public function next(int $length): Forward
    {
        if ($this->offset > strlen($this->buffer)) {
            $node = new Zero;
        } else {
            $node = clone $this;        
            $data = substr($node->buffer, $node->offset, $length);
            if ($data === false) {
                $node->offset = strlen($node->buffer);
                $node->valid = false;
            } else {
                $node->offset += strlen($data);
                $node->valid = true;
            }
            $node->data = (string) $data;
        }
        return $node;
    }

    /**
     * @param int $offset
     * @param int $length
     * @return Random
     */
    public function slice(int $offset, int $length): Random
    {
        if ($this->offset > strlen($this->buffer)) {
            $node = new Zero;
        } else {
            $node = clone $this;        
            $data = substr($node->buffer, $offset, $length);
            if ($data === false) {
                $node->offset = strlen($node->buffer);
                $node->valid = false;
            } else {
                $node->offset = $offset + strlen($data);
                $node->valid = true;
            }
            $node->data = (string) $data;
        }
        return $node;        
    }

}

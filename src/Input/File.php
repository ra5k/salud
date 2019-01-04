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
use Ra5k\Salud\Exception\IoException;
use SplFileObject, Error;

/**
 *
 *
 *
 */
final class File implements Forward, Random
{
    /**
     * @var SplFileObject
     */
    private $file;
    
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
     * @param SplFileObject|string $file
     * @param string $mode
     */
    public function __construct($file, string $mode = 'r')
    {
        $this->file = ($file instanceof SplFileObject) ? $file : new SplFileObject($file, $mode);
        $this->offset = 0;
        $this->data = '';
        $this->valid = !$this->file->eof();
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
     * @throws IoException
     */
    public function next(int $length): Forward
    {
        if ($this->eof()) {
            $node = new Zero;
        } else {
            if ($this->tell() != $this->offset) {
                $this->seek($this->offset);
            }
            $node = clone $this;
            $node->data = $this->read($length);
            $node->offset = $this->tell();
        }
        return $node;
    }

    /**
     * @param int $offset
     * @param int $length
     * @return Random
     * @throws IoException
     */
    public function slice(int $offset, int $length): Random
    {
        if ($this->eof()) {
            $node = new Zero;
        } else {
            if ($this->tell() != $offset) {
                $this->seek($offset);
            }
            $node = clone $this;
            $node->data = $this->read($length);
            $node->offset = $this->tell();
        }
        return $node;        
    }

    /**
     * 
     * @param int $position
     * @return int
     * @throws IoException
     */
    private function seek(int $position)
    {
        $status = $this->file->fseek($position, SEEK_SET);
        if ($status < 0) {
            throw new IoException("Stream is not seekable", 1);
        }
        return $status;
    }

    /**
     * @param int $length
     * @return string
     * @throws IoException
     */
    private function read(int $length)
    {
        try {
            $data = $this->file->fread($length);
            if ($data === false) {
                $name = $this->file->getPathname();
                throw new IoException("Could not read from file '$name'", 1);
            }
        } catch (Error $ex) {
            $name = $this->file->getPathname();
            throw new IoException("Could not read from file '$name'", 1, $ex);
        }
        return $data;
    }
    
    /**
     * @return int
     */
    private function tell()
    {
        return $this->file->ftell();
    }

    private function eof()
    {
        return $this->file->eof();
    }
    
}

<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Uri;


/**
 * A little helper class
 * 
 */
final class Path
{
    /**
     * @var array
     */
    private $path;

    /**
     * @var string
     */
    private $separator;

    /**
     *
     * @param array $path
     */
    public function __construct(array $path, $separator)
    {
        $this->path = $path;
        $this->separator = (string) $separator;
    }

    /**
     * String-cast operator
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode($this->separator, $this->path);
    }

    /**
     * @param string $path
     * @param string $separator
     * @return self
     */
    public static function parse($path, $separator): self
    {
        return new self(explode($separator, $path), $separator);
    }

    /**
     * @return array
     */
    public function nodes(): array
    {
        return $this->path;
    }

    /**
     * @return self
     */
    public function normalize(): self
    {
        $stack = [];
        $modified = false;
        $last = count($this->path) - 1;
        //
        foreach ($this->path as $i => $p) {
            if ($p == '.' || ($p == '' && $i != 0 && $i != $last)) {
                $modified = true;
            } elseif ($p == '..' && !empty($stack) && end($stack)) {
                array_pop($stack);
                $modified = true;
            } else {
                array_push($stack, $p);
            }
        }
        return ($modified) ? new self($stack, $this->separator) : $this;
    }

    /**
     * @return self
     */
    public function join(self $tail): self
    {
        $head = $this->path;
        array_pop($head);
        $path = new self(array_merge($head, $tail->path), $this->separator);
        return $path->normalize();
    }

}

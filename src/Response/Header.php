<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Response;

/**
 * A helper class for HTTP headers
 *
 *
 */
final class Header
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var bool
     */
    private $unique;

    /**
     * @param string $spec
     * @return self
     */
    public static function parse(string $spec, bool $unique = true): self
    {
        $s = ':';
        $c = strpos($spec, ':');
        if ($c === false) {
            $header = new self($spec, '', $unique);
        } else {
            $name = substr($spec, 0, $c);
            $value = trim(substr($spec, $c + strlen($s)));
            $header = new self($name, $value, $unique);
        }
        return $header;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value, bool $unique = true)
    {
        $this->name = $name;
        $this->value = $value;
        $this->unique = $unique;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->line();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return str_replace('_', '-', strtolower($this->name));
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @return string
     */
    public function line(): string
    {
        return $this->name . ': ' . preg_replace('/\R/', '$1  ', $this->value);
    }

}

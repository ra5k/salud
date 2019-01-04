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
use Ra5k\Salud\Exception\{InvalidArgumentException};
use Iterator;


/**
 *
 *
 *
 */
final class Format implements Param
{
    /**
     * @var Param
     */
    private $origin;

    /**
     * @var callable
     */
    private $escape;

    /**
     * @var array
     */
    private $delimiters = ['{{', '}}'];

    /**
     *
     * @param mixed    $data
     * @param callable $escape
     * @param array    $delims
     * @param string   $separator
     * @return self
     */
    public static function simple($data, callable $escape = null, array $delims = null, $separator = null)
    {
        return new self(new Param\Simple($data, null, $separator), $escape, $delims);
    }

    /**
     *
     * @param Param $origin
     * @param callable $escape
     * @param array $delims
     * @throws InvalidArgumentException
     */
    public function __construct(Param $origin, callable $escape = null, array $delims = null)
    {
        $this->origin = $origin;
        $this->escape = $escape ?: function ($value) { return $value; };
        if ($delims) {
            $length = count($delims);
            if ($length != 2) {
                throw new InvalidArgumentException("Argument 3 must have two components, $length given");
            }
            $this->delimiters = array_values($delims);
        }
    }

    /**
     * String-cast operator
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param string|array $key
     * @param int $flags
     * @return self
     */
    public function node($key): Param
    {
        return new self($this->origin->node($key), $this->escape, $this->delimiters);
    }

    /**
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
     *
     * @param mixed $data
     * @param bool $exists
     * @return self
     */
    public function child($data, bool $exists = true): Param
    {
        return new self($this->origin->child($data, $exists), $this->escape, $this->delimiters);
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->origin->data();
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->origin->exists();
    }

    /**
     * Returns the string representation of the data object
     * @return string
     */
    public function toString()
    {
        $data = $this->origin->data();
        $out = '';
        if (is_scalar($data) || is_null($data)) {
            $out = (string) $data;
        } elseif (is_object($data) && method_exists($data, '__toString')) {
            $out = (string) $data;
        }
        return $out;
    }

    /**
     * @return array
     */
    public function delimiters()
    {
        return $this->delimiters;
    }

    /**
     * @return callable
     */
    public function escape()
    {
        return $this->escape;
    }

    /**
     * Substitute variables in the given template
     *
     * @param string $template
     * @return string
     */
    public function render($template)
    {
        $filter = $this->escape;
        $delims = $this->delimiters();

        // The substitution handler:
        $callback = function ($matches) use ($filter) {
            $key = trim($matches[1]);
            if (substr($key, 0, 1) == '&') {
                $key = trim(substr($key, 1));
                $content = $this->node($key)->toString();
            } else {
                $content = $filter($this->node($key)->toString());
            }
            return $content;
        };

        // Substitute the template
        $start = preg_quote($delims[0]);
        $end = preg_quote($delims[1]);
        $content = preg_replace_callback("/{$start}(.+?){$end}/s", $callback, (string) $template);

        return $content;
    }

    /**
     * @return Iterator
     */
    public function items(): Iterator
    {
        return $this->origin->items();
    }

}

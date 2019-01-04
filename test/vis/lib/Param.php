<?php
/*
 * This file is part of the Ra5k Salut library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Standalone;

// [imports]
use Exception,
    RuntimeException,
    Error,
    DateInterval,
    Closure,
    Countable;


/**
 *
 *
 *
 */
final class Param
{
    /**
     * @var mixed
     */
    private $core;

    /**
     * @var bool
     */
    private $exists;

    /**
     * @param mixed $core
     */
    public function __construct($core, $exists = true)
    {
        if ($core instanceof self) {
            $core = $core->core();
        }
        $this->core = $core;
        $this->exists = (bool) $exists;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            $string = $this->str();
        } catch (Exception $e) {
            $string = 'ERROR: ' . $e->getMessage();
        } catch (Error $e) {
            $string = 'ERROR: ' . $e->getMessage();
        }
        return $string;
    }

    /**
     * @param string $key
     * @return self
     */
    public function __invoke($key)
    {
        return $this->node($key);
    }

    /**
     * @param string $prop
     * @return self
     */
    public function __get($prop)
    {
        return isset($this->core[$prop]) ? new self($this->core[$prop], true) : new self(null, false);
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->core[$name]);
    }

    /**
     * @return self
     */
    public function peer($data = null, $exists = null)
    {
        $peer = new self($data, $exists === null ? $this->exists : $exists);
        return $peer;
    }

    /**
     * @return string
     */
    public function str()
    {
        $core = $this->core;
        if (is_array($core)) {
            $first = reset($core);
            $string = (new self($first))->__toString();
        } else {
            $string = (string) $core;
        }
        return $string;
    }

    /**
     *
     */
    public function display()
    {
        $core = $this->core;
        if (is_object($core) && is_callable($core)) {
            $core();
        } else {
            echo $this->str();
        }
    }

    /**
     *
     * @param string $template
     * @param array $delims
     * @return string
     */
    public function resolve($template, array $delims = null)
    {
        $resolve = function ($matches) {
            $key = trim($matches['key']);
            $esc = true;
            if (substr($key, 0, 1) == '&') {
                $esc = false;
                $key = ltrim(substr($key, 1));
            }
            $node = $this->node($key);
            if ($esc) {
                $node = $node->esc();
            }
            return $node->__toString();
        };
        $pattern = $this->regex($delims);
        return preg_replace_callback($pattern, $resolve, $template);
    }

    /**
     * @return mixed
     */
    public function core()
    {
        return $this->core;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->exists;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->core);
    }

    /**
     * @return int
     */
    public function length()
    {
        $core = $this->core;
        if (is_string($core)) {
            $length = strlen($core);
        } elseif (is_array($core) || $core instanceof Countable) {
            $length = count($length);
        } else {
            $length = -1;
        }
        return $length;
    }

    /**
     * @param string $key
     * @return self
     */
    public function node($key)
    {
        $exists = true;
        $data = $this->core;
        $path = ($key == '.') ? [] : explode('.', $key);
        foreach ($path as $p) {
            if (isset($data[$p])) {
                $data = $data[$p];
            } else {
                $data = null;
                $exists = false;
                break;
            }
        }
        return new self($data, $exists);
    }

    /**
     * @return self
     */
    public function esc()
    {
        return new self(htmlspecialchars($this->core));
    }

    /**
     * @return self
     */
    public function trim()
    {
        return new self(trim($this->core));
    }

    /**
     * @return string
     */
    public function age()
    {
        $f = '';
        $i = $this->core();
        if (!($i instanceof DateInterval)) {
            $i = new DateInterval($i);
        }
        if ($i->y) {
            $f = $i->format('%y years');
        } elseif ($i->m) {
            $f = $i->format('%m months');
        } elseif ($i->d) {
            $f = $i->format('%d days');
        } elseif ($i->h) {
            $f = $i->format('%h hours');
        } elseif ($i->i) {
            $f = $i->format('%i minutes');
        } elseif ($i->s) {
            $f = $i->format('%s seconds');
        }
        return $f;
    }

    /**
     * @return int|float
     */
    public function num()
    {
        return is_numeric($this->core) ? $this->core + 0 : -1;
    }

    /**
     * @param string $separator
     * @return self
     */
    public function join($separator)
    {
        $data = (array) $this->core();
        foreach ($data as $k => $v) {
            if (empty($v)) {
                unset($data[$k]);
            }
        }
        return new self(implode($separator, $data));
    }

    /**
     * @param string $item
     * @return self
     */
    public function push($item)
    {
        $core = (array) $this->core;
        $core[] = $item;
        return new self($core);
    }

    /**
     * @return self
     */
    public function up()
    {
        $path = $this->core;
        $dir = rtrim($path, '/');
        $pos = strrpos($dir, '/');
        if ($pos !== false) {
            $dir = substr($dir, 0, $pos);
        }
        return new self($dir ?: '/');
    }

    /**
     * @param string $tail
     * @return self
     */
    public function ref($tail = '')
    {
        $prefix = $this->core;
        $suffix = trim($tail, '/');
        return new self("$prefix/$suffix");
    }

    /**
     * @param array $delims
     * @return string
     */
    private function regex($delims)
    {
        $open = preg_quote(isset($delims[0]) ? $delims[0] : '{');
        $close = preg_quote(isset($delims[1]) ? $delims[1] : '}');
        return "/{$open}(?<key>.+?){$close}/s";
    }

}

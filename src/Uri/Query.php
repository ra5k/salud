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
 * Alternative URI-query parser
 *
 *
 */
final class Query
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var string
     */
    private $query;

    /**
     * @param array|string|self|null $query
     */
    public function __construct($query = null)
    {
        if ($query instanceof self) {
            $this->params = $query->params;
            $this->query  = $query->query;
        } elseif (is_array($query)) {
            $this->params = $query;
        } elseif (is_string($query)) {
            $this->query = $query;
        } elseif (is_null($query)) {
            $this->params = [];
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     *
     * @param string $name
     */
    public function __get($name)
    {
        $params = $this->params();
        return $params[$name];
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        $params = $this->params();
        return array_key_exists($name, $params);
    }

    /**
     * Creates Query object from the current request
     * @return self
     */
    public static function request()
    {
        return new self(filter_input(INPUT_SERVER, 'QUERY_STRING'));
    }

    /**
     * @param array $data
     * @return string
     */
    public function toString()
    {
        if (null === $this->query) {
            $assignments = array ();
            foreach ($this->params as $name => $value) {
                $n = urlencode($name);
                foreach ((array) $value as $x) {
                    $assignments[] = $n . '=' . urlencode($x);
                }
            }
            $this->query = implode("&", $assignments);
        }
        return $this->query;
    }

    /**
     * @return int
     */
    public function length()
    {
        return count($this->params);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function first($name)
    {
        $data = $this->param($name);
        if (is_array($data)) {
            reset($data);
            $data = current($data);
            if ($data === false) {
                $data = null;
            }
        }
        return $data;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function param($name)
    {
        $params = $this->params();
        return isset($params[$name]) ? $params[$name] : null;
    }

    /**
     * @return array
     */
    public function params()
    {
        if (null === $this->params) {
            $this->params = $this->parseQuery($this->query);
        }
        return $this->params;
    }

    /**
     * @param string $query
     * @return array
     */
    private function parseQuery($query)
    {
        $params = array ();
        foreach (explode('&', $query) as $assignment) {
            $this->parseAssign($assignment, $params);
        }
        return $params;
    }

    /**
     * @param string $assignment
     * @param array $params
     */
    private function parseAssign($assignment, array &$params)
    {
        $pos = strpos($assignment, "=");
        if ($pos === false) {
            $pos = strlen($assignment);
        }
        $key = urldecode(substr($assignment, 0, $pos));
        $value = urldecode(substr($assignment, $pos + 1));

        $open = strpos($key, '[');
        if ($open !== false) {
            $close = (strpos($key, ']', $open) ? : strlen($key));
            $var = substr($key, 0, $open);
            $off = substr($key, $open + 1, $close - $open - 1);
            if ($off) {
                $idx = (is_numeric($off) ? (int) $off : $off);
                $params[$var][$idx] = $value;
            } else {
                $params[$var][] = $value;
            }
        } elseif (isset($params[$key])) {
            $ref = & $params[$key];
            if (!is_array($ref)) {
                $ref = array ($ref);
            }
            $ref[] = $value;
        } else {
            $params[$key] = $value;
        }
    }

}

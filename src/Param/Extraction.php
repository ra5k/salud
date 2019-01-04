<?php

/*
 * This file is part of the Salud library
 * (c) 2017 GitHub/ra5k
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Param;


// [imports]
use ArrayAccess;

/**
 *
 *
 */
trait Extraction
{    
    /**
     * @param array $path
     * @param mixed $data
     * @return boolean
     */
    private function pathValue(array $path, &$data)
    {
        $found = true;
        foreach ($path as $k) {
            if (!$this->keyValue($k, $data, $data)) {
                $found = false;
                break;
            }
        }
        if ($found && $this->isCallable($data)) {
            $data = $data();
        }
        return $found;
    }
    
    /**
     * 
     * @param string $key
     * @param mixed $data
     * @param mixed $value
     * @return bool
     */
    private function keyValue($key, $data, &$value)
    {
        $found = false;
        $value = null;
        if (is_array($data)) {
            if (array_key_exists($key, $data)) {
                $value = $data[$key];
                $found = true;
            }
        } elseif (is_object($data)) {
            if (is_callable([$data, $key])) {
                $value = $data->$key();
                $found = true;
            } else if ($data instanceof ArrayAccess && $data->offsetExists($key)) {
                $value = $data->offsetGet($key);
                $found = true;
            } else if (isset($data->$key)) {
                // Note: isset() returns also false if the property exists but is NULL.
                // Unfortunately, property_exists() also returns true if the property is non-public.
                $found = true;
                $value = $data->$key;
            }
        }
        return $found;
    }

    /**
     * @param string|array $key
     * @return array
     */
    private function keyPath($key, $separator)
    {
        if (is_null($key) || $key === $separator) {
            $path = [];
        } else if (is_array($key)) {
            $path = $key;
        } else {
            $path = explode($separator, $key);
        }
        return $path;
    }

    /**
     * We treat only objects as callable
     * @param mixed $object
     */
    private function isCallable($object)
    {
        return is_object($object) && is_callable($object);
    }
    
}

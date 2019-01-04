<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Config;

// [imports]
use XMLReader;

/**
 *
 *
 * @internal This class is used by Std.php for reading XML config files
 */
final class XmlParser
{
    /**
     * @var array
     */
    private static $basics = [
        'binary' => 1,
        'string' => 1,
        'integer' => 1,
        'float' => 1,
        'bool' => 1,
        'null' => 1,
        'int'  => 1,
        'true' => 1,
        'false' => 1,
        'b' => 1,
        'i' => 1,
        's' => 1,
        'f' => 1,
    ];

    /**
     * @var XMLReader
     */
    private $reader;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->reader = new XMLReader();
        $this->reader->open($filename);
    }

    /**
     * @param bool $assoc
     * @return mixed
     */
    public function parse($assoc = false)
    {
        $data = null;
        $reader = $this->reader;
        //
        while ($reader->read()) {
            if ($reader->name == 'object') {
                $data = $this->readObject($assoc);
                break;
            }
        }
        return $data;
    }

    /**
     * @param bool $assoc
     * @return array|object
     */
    private function readObject($assoc = false)
    {
        $object = [];
        $reader = $this->reader;
        $empty = $reader->isEmptyElement;
        //
        while (!$empty && $reader->read()) {
            $type = $reader->nodeType;
            $name = $reader->name;
            $key  = $reader->getAttribute('name');
            if ($type == XMLReader::ELEMENT) {
                if ($name == 'list') {
                    $object[$key] = $this->readList($assoc);
                } elseif ($name == 'object') {
                    $object[$key] = $this->readObject($assoc);
                } elseif (isset (static::$basics[$name])) {
                    $object[$key] = $this->readValue();
                }
            } elseif ($type == XMLReader::END_ELEMENT && $name == 'object') {
                break;
            }
        }
        return ($assoc) ? $object : (object) $object;
    }

    /**
     * @return array
     */
    private function readList($assoc = false)
    {
        $list = [];
        $reader = $this->reader;
        $empty = $reader->isEmptyElement;
        while (!$empty && $reader->read()) {
            $type = $reader->nodeType;
            $name = $reader->name;
            if ($type == XMLReader::ELEMENT) {
                if ($name == 'list') {
                    $list[] = $this->readList();
                } elseif ($name == 'object') {
                    $list[] = $this->readObject($assoc);
                } elseif (isset (static::$basics[$name])) {
                    $list[] = $this->readValue();
                }
            } elseif ($type == XMLReader::END_ELEMENT && $name == 'list') {
                break;
            }
        }
        return $list;
    }

    /**
     * @return int|float|bool|string|binary
     */
    private function readValue()
    {
        $reader = $this->reader;
        $value = $value = $reader->getAttribute('value');
        $empty = $reader->isEmptyElement;
        $open  = $reader->name;
        $done  = false;
        //
        if ($value !== null) {
            $done  = true;
        }
        $accu = '';
        while (!$empty && $reader->read()) {
            $type = $reader->nodeType;
            $name = $reader->name;
            if ($type == XMLReader::END_ELEMENT && $name == $open) {
                break;
            }
            if (!$done && $type == XMLReader::TEXT) {
                $accu .= $reader->value;
            }
        }
        if (!$done) {
            $value = $accu;
        }
        return $this->castValue($value, $open);
    }

    /**
     *
     * @param string $value
     * @param string $type
     * @return mixed
     */
    private function castValue($value, $type)
    {
        if ($type == 'int' || $type == 'float' || $type == 'integer' || $type == 'i' || $type == 'f') {
            $value += 0;
        } elseif ($type == 'bool') {
            if ($value == 'true' || $value == 'yes' || $value == 'on') {
                $value = true;
            } elseif ($value == 'false' || $value == 'no' || $value == 'off') {
                $value = false;
            } else {
                $value = (bool) $value;
            }
        } elseif ($type == 'null') {
            $value = null;
        } elseif ($type == 'string' || $type == 's') {
            $value = (string) $value;
        } elseif ($type == 'binary' || $type == 'b') {
            $value = (binary) $value;
        } elseif ($type == 'true') {
            $value = true;
        } elseif ($type == 'false') {
            $value = false;
        }
        return $value;
    }

}

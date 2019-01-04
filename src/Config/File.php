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
use Ra5k\Salud\Config;
use Ra5k\Salud\Exception\ConfigException;
use Symfony\Component\Yaml;
use SplFileInfo;

/**
 *
 *
 *
 */
class File implements Config
{
    /**
     * The main configuration file
     * @var SplFileInfo
     */
    private $main;

    /**
     * The configuration data
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $includeKey;

    /**
     * @var bool
     */
    private $includeDeep;

    /**
     * @var int
     */
    private $maxDepth;

    /**
     * @var array
     */
    private $errors;

    /**
     *
     * @param string $filename   Name of the (main) configuration file
     * @param bool   $deep       Whether to search recursively for includes
     * @param string $includeKey Name of the include key
     */
    public function __construct($filename, $deep = false, $includeKey = 'include', $maxDepth = 100)
    {
        $this->main = new SplFileInfo($filename);
        $this->includeDeep = (bool) $deep;
        $this->includeKey = (string) $includeKey;
        $this->maxDepth = (int) $maxDepth;
        $this->errors = [];
    }

    /**
     *
     */
    public function data()
    {
        if (null === $this->data) {
            $this->data = $this->load($this->main, 0);
        }
        return $this->data;
    }

    /**
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @param SplFileInfo $file
     * @param int $depth
     *
     * @return array|bool
     * @throws ConfigException
     */
    private function load(SplFileInfo $file, $depth)
    {
        $data = [];
        $ext = $file->getExtension();
        if ($this->testFile($file)) {
            switch ($ext) {
                case 'php' : $data = $this->loadPhp($file); break;
                case 'ini' : $data = $this->loadIni($file); break;
                case 'xml' : $data = $this->loadXml($file); break;
                case 'json': $data = $this->loadJson($file); break;
                case 'yaml': $data = $this->loadYaml($file); break;
                case 'yml' : $data = $this->loadYaml($file); break;
                default: throw new ConfigException("Config file extension ($ext) unknown", 1);
            }
        }
        $this->postProcess($file, $data, $depth);
        return $data;
    }

    /**
     * @param SplFileInfo $file
     * @throws ConfigException
     */
    private function loadPhp(SplFileInfo $file)
    {
        $path = $file->getPathname();
        try {
            $data = include $path;
        } catch (\Exception $e) {
            $m = $e->getMessage();
            throw new ConfigException("Could not load PHP file ($path): $m", 2, $e);
        }
        return $data;
    }

    /**
     * @param SplFileInfo $file
     * @throws ConfigException
     */
    private function loadIni(SplFileInfo $file)
    {
        $path = $file->getPathname();
        try {
            $data = parse_ini_file($path, true);
        } catch (\Exception $e) {
            $m = $e->getMessage();
            throw new ConfigException("Could not load INI file ($path): $m", 2, $e);
        }
        return $data;
    }

    /**
     * @param SplFileInfo $file
     * @throws ConfigException
     */
    private function loadJson(SplFileInfo $file)
    {
        $json = $this->readFile($file);
        $data = json_decode($json, true);
        if ($data === null) {
            $code = json_last_error();
            if ($code != JSON_ERROR_NONE) {
                throw new ConfigException(json_last_error_msg(), $code);
            }
        }
        return $data;
    }

    /**
     *
     * @param SplFileInfo $file
     * @throws ConfigException
     */
    private function loadYaml(SplFileInfo $file)
    {
        $yaml = $this->readFile($file);
        try {
            $parser = new Yaml\Parser();
            $data = $parser->parse($yaml);
        } catch (\Exception $ex) {
            $m = $ex->getMessage();
            $path = $file->getPathname();
            throw new ConfigException("YAML config parsing error in ($path): $m", 4, $ex);
        }
        return $data;
    }

    /**
     * @param string $file
     * @return mixed
     * @throws ConfigException
     */
    private function loadXml(SplFileInfo $file)
    {
        $path = $file->getPathname();
        try {
            $parser = new XmlParser($path);
            $data = $parser->parse(true);
        } catch (\Exception $ex) {
            $m = $ex->getMessage();
            throw new ConfigException("XML config parser error in ($path): $m", 4, $ex);
        }
        return $data;
    }

    /**
     * @param SplFileInfo $file
     * @throws ConfigException
     */
    private function readFile(SplFileInfo $file)
    {
        $length = $file->getSize();
        $path   = $file->getPathname();
        $handle = fopen($path, 'r');
        if ($handle) {
            $data = fread($handle, $length);
            fclose($handle);
        } else {
            $error = error_get_last();
            throw new ConfigException("Cannot open file ($path): {$error['message']}", 2);
        }
        return $data;
    }

    /**
     * @param SplFileInfo $file
     * @return boolean
     */
    private function testFile(SplFileInfo $file)
    {
        $error = false;
        $path = $file->getPathname();

        if (!$file->isFile()) {
            $error = new ConfigException("File ($path) not found", 3);
        } elseif (!$file->isReadable()) {
            $error = new ConfigException("File ($path) is not readable", 2);
        }
        if ($error) {
            $this->errors[] = $error;
        }

        return !$error;
    }

    /**
     * @param mixed $data
     * @throws ConfigException
     */
    private function postProcess(SplFileInfo $file, &$data, $depth)
    {
        if ($depth > $this->maxDepth) {
            throw new ConfigException("Maxmimum include depth of {$this->maxDepth} exceeded", 5);
        }

        $include = $this->includeKey;
        if (isset($data[$include])) {
            foreach ($this->references($data[$include], $data) as $ref) {
                $inc = $this->resolve($file, $ref);
                $src = $this->load($inc, $depth);
                if ($src) {
                    $this->mixin($data, $src);
                }
            }
            unset($data[$include]);
        }

        if ($this->includeDeep && is_array($data)) {
            foreach ($data as &$section) {
                $this->postProcess($file, $section, $depth + 1);
            }
        }
    }

    /**
     *
     * @param mixed $spec
     * @return array
     */
    private function references($spec, $data)
    {
        return (array) $spec;
    }

    /**
     * @param SplFileInfo $source
     * @param string $ref
     * @return SplFileInfo
     */
    private function resolve(SplFileInfo $source, $ref)
    {
        $sep = DIRECTORY_SEPARATOR;
        if (substr($ref, 0, strlen($sep)) == $sep) {
            $resolved = new SplFileInfo($ref);
        } else {
            $path = $source->getPath() . $sep . $ref;
            $resolved = new SplFileInfo($path);
        }
        return $resolved;
    }

    /**
     * @param array $dest
     * @param array $source
     * @return array
     */
    private function mixin(array &$dest, array $source)
    {
        foreach ($source as $key => $value) {
            if (is_int($key)) {
                $dest[] = $value;
            } else {
                if (!isset ($dest[$key]) || !is_array($dest[$key])) {
                    $dest[$key] = [];
                }
                if (is_array($value)) {
                    $this->mixin($dest[$key], $value);
                } else {
                    $dest[$key] = $value;
                }
            }
        }
        return $dest;
    }

}

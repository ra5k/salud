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
use SplFileInfo, Closure;


/**
 *
 */
final class Script
{
    /**
     * The path of this script starting from the root directory
     * @var array
     */
    private $path;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var self
     */
    private $parent;

    /**
     * @var Context
     */
    private $env;

    /**
     *
     */
    public function __construct($path, Context $env)
    {
        $this->path = $this->normalize($this->vector($path));
        $this->env = $env;
    }

    /**
     * @return $this
     */
    public function prepare()
    {
        $meta = $this->meta();
        $base = $meta->base->str() ?: $this->env->layout();
        return $this->extend($base);
    }

    /**
     *
     * @param string $path
     * @return $this
     */
    public function extend($path)
    {
        if ($path) {
            $vector = $this->resolve($path);
            $parent = new self($vector, $this->env);
        } else {
            $parent = null;
        }
        $this->parent = $parent;
        return $this;
    }

    /**
     * Renders the script to the PHP output stream (php://output)
     */
    public function render(array $data = [])
    {
        if ($this->parent) {
            $content = function () use ($data) {
                return $this->load($data);
            };
            $data['content'] = $content;
            $this->parent->render($data);
        } else {
            $this->load($data);
        }
    }

    /**
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
    }

    /**
     * @param mixed $data
     * @return Param
     */
    public function node($data)
    {
        return new Param($data);
    }

    /**
     * Returns the document root
     * @return string
     */
    public function root()
    {
        return $this->env->root();
    }

    /**
     * Returns the URL prefix
     * @return string
     */
    public function base()
    {
        return $this->env->base();
    }

    /**
     * Returns the URL path
     * @return string
     */
    public function path()
    {
        return implode("/", $this->path);
    }

    /**
     * Returns the script path
     * @return string
     */
    public function origin()
    {
        return $this->root() . $this->base() . '/scripts' . $this->path();
    }

    /**
     * @return Params
     */
    public function meta()
    {
        if ($this->meta === null) {
            $config = new Config($this->origin());
            $this->meta = $config->root();
        }
        return $this->meta;
    }

    /**
     * Loads a PHP scripts via PHP ``include``
     *
     * @param array $vars
     * @return mixed
     */
    private function load(array $vars = [])
    {
        // Prepare the view model
        $data = array_merge_recursive($this->meta()->core(), $vars);
        $data['base'] = $this->base();

        // Create the view
        $view = new View($data);
        $render = Closure::bind(function ($filename) {
            return include $filename;
        }, $view, View::class);

        // Render the script
        return $render($this->origin());
    }

    /**
     * @param string $target
     * @return array
     */
    private function resolve($target)
    {
        if (substr($target, 0, 1) == '/') {
            $resolved = $this->vector($target);
        } else {
            $head = $this->path;
            array_pop($head);
            $resolved = $this->normalize(array_merge($head, $this->vector($target)));
        }
        return $resolved;
    }

    /**
     * @param string|array $location
     * @return array
     */
    private function vector($location)
    {
        if (!is_array($location)) {
            if (DIRECTORY_SEPARATOR != '/') {
                $location = str_replace(DIRECTORY_SEPARATOR, '/', $location);
            }
            $location = explode('/', $location);
        }
        return $location;
    }

    /**
     * @param array $vector
     * @return array
     */
    private function normalize(array $vector)
    {
        $normalized = [];
        $last = count($vector) - 1;
        foreach ($vector as $i => $c) {
            if ($c == '.' || ($c == '' && 0 < $i && $i < $last)) {
                continue;
            } elseif ($c == '..') {
                array_pop($normalized);
            } else {
                array_push($normalized, $c);
            }
        }
        return $normalized;
    }

}

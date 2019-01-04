<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Template\Php;

// [imports]
use Ra5k\Salud\Exception\{IoException, InvalidArgumentException};
use SplFileInfo, Closure;


/**
 * Helper class for the PHP Template
 *
 *
 */
final class Script
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var array
     */
    private $templates;

    /**
     * @var array
     */
    private $blocks;

    /**
     * @var bool
     */
    private $primary;

    /**
     * @var Library
     */
    private $library;

    /**
     *
     * @param Library $library
     * @param string $name
     * @param Context $context
     */
    public function __construct(Library $library, string $name, Context $context)
    {
        $this->library = $library;
        $this->name = $name;
        $this->context = $context;
        $this->primary = true;
        $this->templates = [];
        $this->blocks = [];
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
    public function path(): string
    {
        return $this->library->path($this->name);
    }

    /**
     * @return Context
     */
    public function context(): Context
    {
        return $this->context;
    }

    /**
     * @param string $base
     * @param Closure $definition
     * @return $this
     */
    public function extend(string $base, Closure $definition)
    {
        $this->templates[$base] = $definition->bindTo($this, new class {});
        return $this;
    }

    /**
     *
     *
     * @param string $name
     * @param Closure|string|null $definition
     * @return $this
     *
     * TODO: Check whether the object context (Closure::bind) is correct
     */
    public function block(string $name, $definition = null)
    {
        $callback = $this->definition($definition);
        if (!$callback) {
            $type = is_object($definition) ? get_class($definition) : gettype($definition);
            throw new InvalidArgumentException("Argument 2 of wrong type ($type)");
        }

        if (isset($this->blocks[$name])) {
            $callback = $this->blocks[$name];
        } else {
            $this->blocks[$name] = $callback->bindTo($this, new class {});
        }

        if ($this->primary) {
            $callback($this);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function templates(): array
    {
        return $this->templates;
    }

    /**
     * @return array
     */
    public function blocks(): array
    {
        return $this->blocks;
    }

    /**
     * @param string $input
     * @return string
     */
    public function esc(string $input): string
    {
        return htmlspecialchars($input, ENT_COMPAT, 'UTF-8');
    }

    /**
     * @param string $name
     */
    public function execute()
    {
        $include = static function (Script $script) {
            $data = $script->context();
            return include $script->path();
        };
        $include($this);
        foreach ($this->templates() as $name => $template) {
            $base = $this->peer($name);
            //
            $base->primary = false;
            ob_start();
            //
            $template($base);
            //
            ob_end_clean();
            $base->primary = true;
            //
            $base->execute();
        }
        return '';
    }

    /**
     * @param string $name
     * @return self
     * @throws RuntimeException
     */
    public function peer(string $name): self
    {
        $resolved = $this->library->resolve($name, $this);
        $peer = new self($this->library, $resolved, $this->context());
        if (!file_exists($peer->path())) {
            throw new RuntimeException("Script '$name' ($resolved) not found");
        }
        return $peer;
    }

    /**
     * Convenience function
     * @param string $peer
     */
    public function call(string $peer)
    {
        return $this->peer($peer)->execute();
    }

    /**
     * @param string|null|callable $spec
     * @return Closure|null
     */
    private function definition($spec)
    {
        if (is_string($spec)) {
            $def = function () use ($spec) { echo $spec; };
        } else if (is_null($spec)) {
            $def = function () { };
        } else if (is_callable($spec) && is_object($spec)) {
            $def = $spec;
        } else {
            $def = null;
        }
        return $def;
    }

}

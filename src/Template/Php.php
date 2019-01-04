<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 */

namespace Ra5k\Salud\Template;

// [imports]
use Ra5k\Salud\{Template};



/**
 *
 *
 */
final class Php implements Template
{
    /**
     * @var Php\Library
     */
    private $library;

    /**
     * @var string
     */
    private $seed;

    /**
     *
     * @param string $seed
     * @param string $base
     * @param bool $relative
     */
    public function __construct(string $seed, string $base, bool $relative = false)
    {
        $this->seed = $seed;
        $this->library = new Php\Library($base, $relative);
    }

    /**
     * @param mixed $context
     */
    public function render($context)
    {
        $script = $this->library->script($this->seed, $context);
        $script->execute();
    }

}

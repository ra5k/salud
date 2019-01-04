<?php
/*
 * This file is part of the Ra5k Salut library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Standalone;


/**
 *
 *
 */
final class Context
{
    //
    const DEFAULT_LAYOUT = '/share/layout.php';

    /**
     * @var string
     */
    private $messages = [
        404 => "Not Found",
        403 => "Forbidden"
    ];

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $base;

    /**
     * @var string
     */
    private $branch;

    /**
     * @var string
     */
    private $layout;
    
    /**
     *
     */
    public function __construct($branch = '', $layout = self::DEFAULT_LAYOUT)
    {
        $this->branch = $branch;
        $this->layout = $layout;
    }

    /**
     * Returns the file system path for the current request
     * @return string
     */
    public function origin()
    {
        return $this->root() . $this->base() . $this->branch() . $this->path();
    }

    /**
     * @return string
     */
    public function branch()
    {
        return $this->branch;
    }

    /**
     * Returns the common URL prefix.
     *
     * An empty string means that the application is running from the document root.
     * @return string
     */
    public function base()
    {
        if ($this->base === null) {
            $this->base = $this->fetchBase();
        }
        return $this->base;
    }

    /**
     * Returns the document root
     * @return string
     */
    public function root()
    {
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    }

    /**
     * Returns the request path
     * @return string
     */
    public function path()
    {
        if ($this->path === null) {
            $base = $this->base();
            $request = filter_input(INPUT_SERVER, 'REQUEST_URI');
            $cut = strrpos($request, '?');
            if ($cut !== false) {
                $request = substr($request, 0, $cut);
            }
            $this->path = substr($request, strlen($base));
        }
        return $this->path;
    }

    /**
     * @param int $status
     * @return string
     */
    public function message($status)
    {
        return isset($this->messages[$status]) ? $this->messages[$status] : 'Error';
    }

    /**
     * Returns the default layout path
     * @return string
     */
    public function layout()
    {
        return $this->layout;
    }
    
    /**
     * @return string
     */
    private function fetchBase()
    {
        if (PHP_SAPI == 'cli-server') {
            $base = '';
        } else {
            $base = filter_input(INPUT_SERVER, 'SCRIPT_NAME');
            $cut = strrpos($base, '/');
            if ($cut !== false) {
                $base = substr($base, 0, $cut);
            }
        }
        return $base;
    }

}

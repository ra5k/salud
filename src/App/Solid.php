<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\App;

use Ra5k\Salud\{App, Log, Service, Request, Transport};
use Ra5k\Salud\{Exception\ErrorException, Exception\InvalidArgumentException};
use Psr\Log\LogLevel;
use Throwable;


/**
 *
 *
 *
 */
final class Solid implements App
{
    /**
     * @var Service
     */
    private $service;

    /**
     * @var Log
     */
    private $log;

    /**
     * Debug mode
     * @var bool
     */
    private $debug;

    /**
     *
     * @param Service $service
     * @param Request $request
     * @param Log|string|null $log
     * @param bool $debug
     */
    public function __construct(Service $service, $log = null, bool $debug = false)
    {
        if ($log instanceof Log) {
            // pass;
        } else if (is_string($log) && $log != '') {
            $log = new Log\Stream($log, LogLevel::ERROR);
        } else if (is_null($log)) {
            if (PHP_SAPI == 'cli-server') {
                $log = new Log\Stream('php://stderr', LogLevel::ERROR);
            } else {
                $log = new Log\System();
            }
        } else {
            $type = is_object($log) ? get_class($log) : gettype($log);
            throw new InvalidArgumentException("Argument 2 is of wrong type ($type)");
        }
        $this->service = $service;
        $this->log = $log;
        $this->debug = $debug;
    }

    /**
     *
     */
    public function run()
    {
        set_error_handler([ErrorException::class, 'handler']);
        try {
            $request = new Request\Sapi();
            $response = $this->service->handle($request);
            $transport = new Transport\Php($response);
            $transport->sendHeaders($response);
            try {
                $transport->sendBody($response);
            } catch (Throwable $ex) {
                $this->logException($ex);
                echo $this->errorBlock($ex);
            }
        } catch (Throwable $ex) {
            $this->logException($ex);
            echo $this->errorPage($ex);
        }
        restore_error_handler();
    }

    /**
     * The fallback error page
     * @param Throwable $exception
     */
    private function errorPage(Throwable $exception)
    {
        $token = $this->log->token();
        if ($this->debug) {
            $view = new Error\DebugPage($exception, $token);
            $html = $view->page()->content();
        } else {
            $view = new Error\DiscretePage($exception, $token);
            $html = $view->page()->content();
        }
        return $html;
    }

    /**
     * The fallback error block
     * @param Throwable $exception
     */
    private function errorBlock($exception)
    {
        $token = $this->log->token();
        if ($this->debug) {
            $view = new Error\DebugPage($exception, $token);
            $buff = new Error\Buffer();
            $buff->write("<style>");
            $view->styles($buff->sub('    '));
            $buff->write("</style>");
            $view->body($buff);
            $html = $buff->content();
        } else {
            $view = new Error\DiscretePage($exception, $token);
            $buff = new Error\Buffer();
            $buff->write("<style>");
            $view->styles($buff->sub('    '));
            $buff->write("</style>");
            $view->body($buff);
            $html = $buff->content();
        }
        return $html;
    }

    /**
     *
     * @param Throwable $error
     */
    private function logException($error)
    {
        $format = "in %s(%d): %s";
        $this->log->error(sprintf($format, $error->getFile(), $error->getLine(), $error->getMessage()));
    }

}

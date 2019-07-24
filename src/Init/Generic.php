<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Init;

// [imports]
use Ra5k\Salud\{Init, Sapi, Log, Param, Config, Service, Request, Transport};
use Ra5k\Salud\{Exception\ErrorException, Exception\ConfigException};
use Psr\Log\LogLevel;
use Throwable;


/**
 *
 *
 *
 */
final class Generic implements Init
{
    /**
     * @var Sapi
     */
    private $sapi;
    
    /**
     * @var Config|string|array
     */
    private $config;
    
    /**
     * @var Param
     */
    private $params;
    
    /**
     * @var Log\Multi
     */
    private $log;
    
    /**
     * @var bool
     */
    private $trace;
    
    /**
     * @param Config|Param|string|array $config
     */
    public function __construct($config, Sapi $sapi = null)
    {
        $this->config = $config;
        $this->sapi = $sapi ?? new Sapi\Auto();
    }

    /**
     * @return Param
     */
    public function config(): Param
    {
        if ($this->params === null) {
            $config = $this->config;
            if (is_string($config)) {
                $config = new Config\File($config);
                $this->params = new Param\Simple($config->data());
            } else if (is_array($config)) {
                $this->params = new Param\Simple($config);
            } else if ($config instanceof Config) {
                $this->params = new Param\Simple($config->data());
            } else if ($config instanceof Param) {
                $this->params = $config;
            } else {
                $type = is_object($config) ? get_class($config) : gettype($config);
                throw new ConfigException("Configuration is of wrong type ($type)");
            }
        }
        return $this->params;
    }

    /**
     * @return string
     */
    public function env(): string
    {
        return (string) $this->config()->value('application.environment', '');
    }

    /**
     * @return Log
     */
    public function log(): Log
    {
        return $this->multiLog();
    }

    /**
     * 
     * @param Service $service
     */
    public function run(Service $service)
    {
        set_error_handler([ErrorException::class, 'handler']);
        try {
            $request = new Request\Solid($this->sapi);
            $response = $service->handle($request);
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
     * @return Log\Multi
     */
    private function multiLog(): Log\Multi
    {
        if ($this->log === null) {
            $config = $this->config()->node('log');
            $log = new Log\Multi();
            $file = $config->value('filename');
            $level = $config->value('level') ?? ($this->debugMode() ? LogLevel::DEBUG : LogLevel::WARNING);
            if ($file) {
                $colored = (bool) $config->value('colored');
                $log->add(new Log\Limited(new Log\Stream($file), $level, $colored));
            } else {
                $log->add(new Log\System);
                $log->warning("Log file not configured");
            }
            if (PHP_SAPI == 'cli-server') {
                $log->add(new Log\Stream('php://stderr', 'D M d H:i:s Y'));
            }
            $this->log = $log;
        }
        return $this->log;
    }
    
    /**
     * The fallback error block
     * @param Throwable $exception
     */
    private function errorBlock($exception)
    {
        $token = $this->multiLog()->token();
        if ($this->debugMode()) {
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
     * The fallback error page
     * @param Throwable $exception
     */
    private function errorPage(Throwable $exception)
    {
        $token = $this->multiLog()->token();
        if ($this->debugMode()) {
            $view = new Error\DebugPage($exception, $token);
            $html = $view->page()->content();
        } else {
            $view = new Error\DiscretePage($exception, $token);
            $html = $view->page()->content();
        }
        return $html;
    }

    /**
     *
     * @param Throwable $error
     */
    private function logException(Throwable $error)
    {
        $log = $this->log();
        $log->error(sprintf("in %s(%d): %s", $error->getFile(), $error->getLine(), $error->getMessage()));
        if ($this->logTraceEnabled()) {
            foreach ($error->getTrace() as $node) {
                $class = $this->logClassName($node['class'] ?? '');
                $func = $node['function'] ?? '';
                $log->error("  + in {{file}}({{line}}) FUNCTION {{method}}({{args}})", [
                    'file' => $node['file'] ?? 'UNKNOWN',
                    'line' => $node['line'] ?? '?',
                    'method' => $class ? "$class::$func" : "$func",
                    'args' => implode(", ", $this->debugArgs($node['args'] ?? [])),
                ]);
            }
        }
    }
    
    /**
     * @param array $args
     * @return array
     */
    private function debugArgs(array $args): array
    {
        $info = [];
        foreach ($args as $a) {
            $info[] = is_object($a) ? $this->logClassName(get_class($a)) : gettype($a);
        }
        return $info;
    }
    
    /**
     * @return bool
     */
    private function debugMode(): bool
    {
        return ($this->env() == 'development');
    }
    
    /**
     * @return bool
     */
    private function logTraceEnabled(): bool
    {
        if ($this->trace === null) {
            $this->trace = (bool) $this->config()->value('log.trace', $this->debugMode());
        }
        return $this->trace;
    }
    
    /**
     * 
     * @param string $class
     * @return string
     */
    private function logClassName(string $class): string
    {
        return preg_replace(['|^Ra5k\\\\Salud\\\\|', '|^class@anonymous.*|'], ['~', 'class@anonymous'], $class ?? '');
    }
    
}

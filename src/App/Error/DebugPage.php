<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\App\Error;

// [imports]
use Throwable;


/**
 *
 *
 *
 */
final class DebugPage
{
    /**
     * @var Throwable
     */
    private $error;

    /**
     * The log-token
     * @var string
     */
    private $token;

    /**
     * @var float
     */
    private $elapsed;

    /**
     * @param Throwable $error
     */
    public function __construct(Throwable $error, $token = null)
    {
        $this->error = $error;
        $this->token = $token;
        $this->elapsed = $this->elapsed();
    }

    /**
     * @param Buffer $buffer
     * @return Buffer
     */
    public function page(Buffer $buffer = null)
    {
        $buf = $buffer ?: new Buffer();
        $sub = $buf->sub('    ');
        $buf('<!DOCTYPE html>');
        $buf('<html>');
        $buf('  <head>');
        $this->head($sub);
        $buf('  </head>');
        $buf('  <body>');
        $this->body($sub);
        $buf('  </body>');
        $buf('</html>');
        return $buf;
    }

    /**
     * @param Buffer $buffer
     * @return Buffer
     */
    public function head(Buffer $buffer = null)
    {
        $buf = $buffer ?: new Buffer();
        $buf('<meta charset="UTF-8">');
        $buf('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">');
        $buf('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
        $buf('<title>%s</title>', $this->message());
        $buf('<style>');
        $this->styles($buf->sub('  '));
        $buf('</style>');
        return $buf;
    }

    /**
     * @param Buffer $buffer
     * @return Buffer
     */
    public function styles(Buffer $buffer = null)
    {
        $buf = $buffer ?: new Buffer();
        $buf('body { font-family: monospace; color: #666666; }');
        $buf('.error-page { margin: 80px auto; display: table; }');
        $buf('.error-page .error { color: #A94442 }');
        $buf('.error-page .header { background-color: #F7F7F7; padding: 10px 5px; }');
        $buf('.error-page .location { color: #3c763d; margin-bottom: .25em; }');
        $buf('.error-page .title { margin: 0; font-weight: bold; font-size: 1.17em; }');
        $buf('.error-page .trace li { margin-bottom: 1em; }');
        $buf('.error-page .trace .title { font-weight: bold }');
        $buf('.error-page .trace .line { color: #D57B03 }');
        $buf('.error-page .token { font-style: monospace; border-top: 1px solid #666666; }');
        $buf('.error-page .closure { color: #950078 }');
        return $buf;
    }

    /**
     * @param Buffer $buffer
     * @return Buffer
     */
    public function body(Buffer $buffer = null)
    {
        $buf = $buffer ?: new Buffer();
        $buf('<div class="error-page">');
        $buf('  <div class="header">');
        $buf('    <div class="title">');
        $buf('      <span class="error">%s</span>', $this->message());
        $buf('      <span class="code" title="code">[code:%d]</span>', $this->code());
        $buf('    </div>');
        $buf('  </div>');
        $buf('  <ol class="trace">');
        $buf('     <li><div class="title location">%s:<span class="line">%d<span></div></li>', $this->file(), $this->line());
        $this->backtrace($buf->sub('    '));
        $buf('  </ol>');
        if ($this->token || $this->elapsed) {
            $buf('  <div class="token">');
            if ($this->elapsed) {
                $buf('    <div>Elapsed = %.4f ms</div>', $this->elapsed);
            }
            if ($this->token) {
                $buf('    <div>Token = %s</div>', $this->token);
            }
            $buf('  </div>');
        }
        $buf('</div>');
        return $buf;
    }

    /**
     * @param Buffer $buffer
     * @return Buffer
     */
    public function backtrace(Buffer $buffer = null)
    {
        $buf = $buffer ?: new Buffer();
        foreach ($this->trace() as $item) {
            $buf('  <li>');
            $this->item($item, $buf->sub('    '));
            $buf('  </li>');
        }
        return $buf;
    }

    /**
     *
     * @param array $item
     * @param Buffer $buf
     */
    private function item($item, Buffer $buf)
    {
        if (isset($item['file'])) {
            $buf('<div class="title">%s:<span class="line">%d</span></div>', $item['file'], $item['line']);
        } else {
            $buf('<div class="title">&lt;File not available&gt;</div>');
        }
        if (isset($item['function'])) {
            $css_class = trim("function " . $this->indicator($item['function']));
            $function = sprintf('<span class="%s">%s</span>', $css_class, $this->escape($item['function']));
            if (isset($item['class'])) {
                $class = sprintf('<span class="class">%s</span>', $this->escape($item['class']));
                $function = $class . '::' . $function;
            }
            $arguments = '';
            if (isset($item['args'])) {
                $arguments = $this->arguments($item['args']);
            }
            $buf->write('<code>%s(%s)</code>', $function, $arguments);
        }
        return $buf;
    }

    /**
     * @param array $args
     * @return string
     */
    private function arguments(array $args)
    {
        $input = (array) $args;
        $pieces = [];
        foreach ($input as $arg) {
            $spec = is_object($arg) ? get_class($arg) : gettype($arg);
            if (is_string($arg)) {
                $spec .= '[' . strlen($arg) . ']';
            } elseif (is_array($arg)) {
                $spec .= '[' . count($arg) . ']';
            }
            $pieces[] = $spec;
        }
        return implode(", ", $pieces);
    }

    private function code()
    {
        return $this->error->getCode();
    }

    private function message()
    {
        return $this->escape($this->error->getMessage());
    }

    private function file()
    {
        return $this->escape($this->error->getFile());
    }

    private function line()
    {
        return $this->error->getLine();
    }

    private function trace()
    {
        return (array) $this->error->getTrace();
    }

    /**
     * @param string $string
     * @return string
     */
    private function escape($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
    }

    /**
     * @param string $name
     * @return string
     */
    private function indicator($name)
    {
        if ($name == '{closure}') {
            $indicator = "closure";
        } else {
            $indicator = '';
        }
        return $indicator;
    }

    private function elapsed()
    {
        if (defined('START_TIME')) {
            $start = constant('START_TIME');
            $elapsed = (microtime(true) - $start) * 1000;
        }
        return $elapsed;
    }

}

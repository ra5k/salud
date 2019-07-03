<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Init\Error;

// [imports]
use Ra5k\Salud\Response\Status;
use Throwable;


/**
 *
 *
 *
 */
final class DiscretePage
{
    /**
     * @var Throwable
     */
    private $error;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $message;

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
    public function __construct($error, $token = null, $status = 500, $message = null)
    {
        $this->error = $error;
        $this->status = (int) $status;
        $this->message = $message ?: Status::text($status);
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
        $buf('body { font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif; }');
        $buf('.error-page { display: table; margin: 100px auto; color: #666666; }');
        $buf('.error-page .code { color: #888888; font-size: 42pt; }');
        $buf('.error-page .message { padding-left: 20px; font-size: 16pt; }');
        $buf('.error-page .token { font-family: monospace; border-top: 1px solid #ccc; padding-top: 5px; }');
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
        $buf('  <div class="row">');
        $buf('    <span class="code">%d</span>', $this->status());
        $buf('    <span class="message">%s</span>', $this->message());
        $buf('  </div>');
        $buf('  <div class="row">');
        $buf('    <div class="token" title="Error token">%s</div>', $this->token);
        $buf('  </div>');
        $buf('</div>');
        return $buf;
    }


    private function status()
    {
        return $this->status;
    }

    private function message()
    {
        return $this->message;
    }

    /**
     * @param string $string
     * @return string
     */
    private function escape($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
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

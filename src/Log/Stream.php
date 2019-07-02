<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Log;

// [imports]
use Ra5k\Salud\{Param, Sapi, Util};
use Psr\Log\LogLevel;

/**
 *
 *
 *
 */
final class Stream extends Base
{
    // Color end sequence
    const C_END = "\033[0m";

    // Default date format
    const F_DATE = "Y-m-d H:i:s";

    /**
     * Order of the error levels
     * @var array
     */
    private static $colors = [
        LogLevel::EMERGENCY  => "\033[1;37m\033[41m",
        LogLevel::ALERT => "\033[1;35m",
        LogLevel::CRITICAL => "\033[1;31m",
        LogLevel::ERROR => "\033[0;31m",
        LogLevel::WARNING => "\033[0;33m",
        LogLevel::NOTICE => "\033[0;32m",
        LogLevel::INFO => "\033[0m",
        LogLevel::DEBUG => "\033[1;30m",
    ];

    /**
     * @var resource
     */
    private $file;

    /**
     * Date format
     * @var string
     */
    private $format;

    /**
     * Color mode enabled?
     * @var bool
     */
    private $colored;

    /**
     * Remote info string
     * @var string
     */
    private $remote;

    /**
     *
     * @param string $filename
     * @param string $format
     * @param bool $colored
     * @param string $remote
     */
    public function __construct(string $filename, string $format = self::F_DATE, bool $colored = null, string $remote = null)
    {
        parent::__construct();
        $this->file = fopen($filename, 'a');
        $this->format = $format;
        $this->colored = $colored ?? $this->detectTerminal($this->file);
        $this->remote = $remote ?? self::remote();
    }

    /**
     *
     */
    public function __destruct()
    {
        fclose($this->file);
    }

    /**
     *
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $format  = Param\Format::simple($context, null);
        $content = $format->render($message);
        $prefix = $this->prefix($level);
        $this->write($level, "$prefix $content");
        return $this;
    }

    /**
     * @param string $level
     * @return string
     */
    public function prefix($level): string
    {
        $prefix = [];

        $timestamp = $this->timestamp();
        if ($timestamp) {
            $prefix = ["[$timestamp]"];
        }

        if ($this->remote) {
            $prefix[] = $this->remote;
        }

        $token = $this->token();
        if ($token) {
            $prefix[] = "[$token]";
        }

        $prefix[] = "[$level]";
        //
        return implode(" ", $prefix);
    }

    /**
     * @return string
     */
    public function timestamp(): string
    {
        return date($this->format);
    }

    /**
     * Fetch the remote IP address and port from the server variables
     * @return string
     */
    public static function remote(): string
    {
        $sapi = new Sapi\Auto();
        $addr = $sapi->param('REMOTE_ADDR');
        $port = $sapi->param('REMOTE_PORT');
        $remote = (string) $addr;
        if ($port) {
            $remote .= ":$port";
        }
        return $remote;
    }

    /**
     * @param string $message
     */
    private function write($level, $message)
    {
        if ($this->colored && isset(self::$colors[$level])) {
            fwrite($this->file, self::$colors[$level]);
        }
        fwrite($this->file, $message);
        if ($this->colored) {
            fwrite($this->file, self::C_END);
        }
        fwrite($this->file, "\n");
    }

    /**
     * @param stream $stream
     * @return bool
     */
    private function detectTerminal($stream)
    {
        $term = false;
        if (function_exists('stream_isatty')) {
            $term = stream_isatty($stream);
        } else if (function_exists('posix_isatty')) {
            $term = posix_isatty($stream);
        } else {
            $info = new Util\StreamInfo(fstat($stream));
            $term = ($info->mode() == Util\StreamInfo::S_IFCHR);
        }
        return $term;
    }

}

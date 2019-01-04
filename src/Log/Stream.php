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
use Ra5k\Salud\{Param, System\StreamInfo};
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
     * @param string $filename
     */
    public function __construct(string $filename, $format = self::F_DATE, $colored = null)
    {
        parent::__construct();
        $this->file = fopen($filename, 'a');
        $this->format = $format;
        $this->colored = ($colored === null) ? $this->detectTerminal($this->file) : $colored;
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
        $timestamp = $this->timestamp();
        $token = $this->token();
        $format  = Param\Format::simple($context, null, ['{', '}']);
        $content = $format->render($message);
        $this->write($level, "[$timestamp] [$token] [$level] $content");
        return $this;
    }

    /**
     * @return string
     */
    public function timestamp()
    {
        return date($this->format);
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
            $info = new StreamInfo(fstat($stream));
            $term = ($info->mode() == StreamInfo::S_IFCHR);
        }
        return $term;
    }

}

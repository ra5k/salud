<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Response;

// [imports]
use Ra5k\Salud\System;


/**
 * A helper class for the HTTP status
 *
 *
 */
final class Status
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var array
     */
    private static $messages = [
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-Status",
        //
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        //
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Time-out",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Request Entity Too Large",
        414 => "Request-URL Too Long",
        415 => "Unsupported Media Type",
        416 => "Requested range not satisfiable",
        417 => "Expectation Failed",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        444 => "No Response",
        451 => "Protect the Books",   // :-)
        //
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        504 => "Service Unavailable",
        504 => "Gateway Time-out",
        505 => "HTTP Version not supported",
        506 => "Variant Also Negotiates",
        507 => "Insufficient Storage",
        508 => "Loop Detected",
        509 => "Bandwidth Limit Exceeded",
    ];

    /**
     *
     * @param int $code
     * @param string $message
     * @param string $protocol
     * @param string $version
     */
    public function __construct(int $code = 200, string $message = '', string $protocol = '')
    {
        if (!$protocol) {
            $sys = new System\Context();
            $protocol = $sys->server('SERVER_PROTOCOL') ?: 'HTTP/1.1';
        }
        if (!$message && isset(self::$messages[$code])) {
            $message = self::$messages[$code];
        }
        $this->code = (int) $code;
        $this->message = $message;
        $this->protocol = $protocol;
    }

    /**
     * @param int $code
     * @return string|null
     */
    public static function text(int $code)
    {
        return isset(self::$messages[$code]) ? self::$messages[$code] : null;
    }

    /**
     * @return string
     */
    public function line(): string
    {
        return sprintf('%s %d %s', $this->protocol(), $this->code(), $this->message());
    }

    /**
     * @return int
     */
    public function code(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function protocol(): string
    {
        return $this->protocol;
    }

}

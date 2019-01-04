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
use Ra5k\Salud\Param;
use Psr\Log\LogLevel;

/**
 *
 *
 *
 */
final class Buffer extends Base
{
    /**
     * @var string
     */
    private $buffer;

    /**
     * @param string $threshold
     */
    public function __construct($threshold = LogLevel::ERROR)
    {
        parent::__construct();
        $this->buffer = '';
    }

    /**
     * @return string
     */
    public function buffer()
    {
        return $this->buffer;
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
        $format  = Param\Format::simple($context, null, ['{', '}']);
        $content = $format->render($message);
        $this->write("[$timestamp] [$level] $content\n");
        return $this;
    }

    /**
     * @return string
     */
    public function timestamp()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * @param string $message
     */
    private function write($message)
    {
        $this->buffer .= $message;
    }

}

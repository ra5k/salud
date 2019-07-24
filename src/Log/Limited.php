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
use Ra5k\Salud\Log;
use Psr\Log\{LogLevel, LoggerTrait as SingleMethod};

/**
 *
 *
 *
 */
final class Limited implements Log
{
    use SingleMethod;
    
    /**
     * Order of the error levels
     * @var array
     */
    private static $order = [
        LogLevel::EMERGENCY  => 1,
        LogLevel::ALERT => 2,
        LogLevel::CRITICAL => 3,
        LogLevel::ERROR => 4,
        LogLevel::WARNING => 5,
        LogLevel::NOTICE => 6,
        LogLevel::INFO => 7,
        LogLevel::DEBUG => 8,
    ];

    /**
     * @var Log
     */
    private $origin;

    /**
     * @var int
     */
    private $threshold;

    /**
     * @param Log $origin
     * @param string $threshold
     */
    public function __construct(Log $origin, $threshold = LogLevel::ERROR)
    {
        $this->origin = $origin;
        $this->threshold = $this->levelOrder($threshold);
    }

    /**
     *
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $weight = $this->levelOrder($level);
        if ($weight <= $this->threshold) {
            $this->origin->log($level, $message, $context);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function threshold()
    {
        return $this->threshold;
    }

    /**
     * @param string $level
     * @return int
     */
    private function levelOrder($level)
    {
        return self::$order[$level] ?? (int) $level;
    }

}

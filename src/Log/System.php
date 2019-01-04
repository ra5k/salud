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
use Ra5k\Salud\{Param};

/**
 *
 *
 *
 */
final class System extends Base
{
    /**
     * @var int
     */
    private $target;

    /**
     * @param int $target
     */
    public function __construct($target = 0)
    {
        parent::__construct();
        $this->target = $target;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $token = $this->token();
        $format  = new Param\Format(new Param\Simple($context));
        $content = $format->render($message);
        error_log("[$token] [$level] $content", $this->target);
    }

}

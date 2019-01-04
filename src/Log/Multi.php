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

/**
 *
 *
 *
 */
final class Multi extends Base
{
    /**
     * @var Log[]
     */
    private $targets;

    /**
     *
     * @param Log[] $targets
     */
    public function __construct(Log ...$targets)
    {
        parent::__construct();
        $this->targets = $targets;
    }

    /**
     * @param Log $log
     * @return self
     */
    public function add(Log $log): self
    {
        $this->targets[] = $log;
        return $this;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        foreach ($this->targets as $log) {
            $log->log($level, $message, $context);
        }
    }

}

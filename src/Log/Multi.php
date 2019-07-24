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
use Psr\Log\{LoggerTrait as SingleMethod};

/**
 *
 *
 *
 */
final class Multi implements Log
{
    use SingleMethod;
    
    /**
     * @var Log[]
     */
    private $targets;

    /**
     * @var string
     */
    private $token;
    
    /**
     *
     * @param Log[] $targets
     */
    public function __construct(Log ...$targets)
    {
        $this->targets = $targets;
    }

    /**
     * @return string
     */
    public function token(): string
    {
        if ($this->token === null) {
            $this->token = substr(md5(mt_rand() + time()), 0, 6);
        }
        return $this->token;
    }
    
    /**
     * @param Log $target
     * @return self
     */
    public function add(Log $target): self
    {
        $this->targets[] = $target;
        return $this;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $token = $this->token();
        foreach ($this->targets as $log) {
            $log->log($level, "[$token] $message", $context);
        }
    }

}

<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Fork;

// [imports]
use Ra5k\Salud\{Request, Fork, Indicator};
use Ra5k\Salud\Exception\TypeError;

/**
 *
 *
 *
 */
final class Chain implements Fork
{
    /**
     * @var Fork[]
     */
    private $chain;

    /**
     * @param Fork[] $chain
     * @throws TypeError
     */
    public function __construct(array $chain)
    {
        foreach ($chain as $offset => $node) {
            if ($node instanceof Fork) {
                continue;
            } else {
                $type = is_object($node) ? get_class($node) : gettype($node);
                throw new TypeError("Element at $offset is not a Fork, $type given");
            }
        }
        $this->chain = $chain;
    }

    /**
     * @param Request $request
     * @param array $context
     * @return Indicator
     */
    public function route(Request $request, array $context): Indicator
    {
        $target = null;
        foreach ($this->chain as $fork) {
            $t = $fork->route($request, $context);
            if ($t->isMatch()) {
                $target = $t;
                break;
            }
        }
        return $target ?: new Indicator\None;
    }

}

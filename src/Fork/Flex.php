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
use Ra5k\Salud\{Service, Fork, Response, Exception\InvalidArgumentException};

/**
 *
 *
 */
trait Flex
{

    /**
     * @param Fork $spec
     * @param int $position
     * @return Fork
     * @throws InvalidArgumentException
     */
    protected function toNode($spec, int $position = 1): Fork
    {
        if ($spec instanceof Fork) {
            $node = $spec;
        } else if ($spec instanceof Service) {
            $node = new Leaf($spec);
        } else if ($spec instanceof Response) {
            $node = new Fixed($spec);
        } else if (is_array($spec)) {
            $node = new Chain($spec);
        } else if (is_callable($spec)) {
            $node = new Proxy($spec);
        } else if ($spec === false) {
            $node = new Dummy();
        } else {
            $type = is_object($spec) ? get_class($spec) : gettype($spec);
            throw new InvalidArgumentException("Argument $position is of wrong type ($type)");
        }
        return $node;
    }

}

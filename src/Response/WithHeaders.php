<?php
/*
 * This file is part of the Salud library
 * (c) 2017 GitHub/ra5k
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Response;

// [imports]
use Ra5k\Salud\{Response, Exception\InvalidArgumentException};

/**
 *
 *
 *
 */
final class WithHeaders extends Wrap
{
    /**
     * @var Status
     */
    private $headers;

    /**
     * @param Response $origin
     * @param array $headers
     * @throws InvalidArgumentException
     */
    public function __construct(Response $origin, array $headers)
    {
        parent::__construct($origin);
        foreach ($headers as $k => $v) {
            if ($v instanceof Header) {
                // pass;
            } else if (is_string($v)) {
                $headers[$k] = Header::parse($v);
            } else {
                $type = is_object($v) ? get_class($v) : gettype($v);
                throw new InvalidArgumentException("Element at ($k) is not a valid type ($type)");
            }
        }
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function headers(): array
    {
        return array_merge(parent::headers(), $this->headers);
    }

}

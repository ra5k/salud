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
use Ra5k\Salud\{Request, Response, Service};
use Ra5k\Salud\{Fork, Indicator, Exception\BadMethodCallException};

/**
 * Routing via callback
 *
 *
 */
final class Proxy implements Fork
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     *
     * @param Request $request
     * @param array $context
     * @return Indicator
     * 
     * @throws BadMethodCallException
     */
    public function route(Request $request, array $context): Indicator
    {
        $callback = $this->callback;
        $result = $callback($request, $context);
        //
        if ($result instanceof Indicator) {
            // pass;
        } else if ($result instanceof Service) {
            $result = new Indicator\Instant($result->handle($request));
        } else if ($result instanceof Response) {
            $result = new Indicator\Instant($result);
        } else if ($result === false) {
            $result = new Indicator\None();
        } else {
            $type = is_object($result) ? get_class($result) : gettype($result);
            throw new BadMethodCallException("Callback returned invalid type ($type)");
        }
        return $result;
    }

}

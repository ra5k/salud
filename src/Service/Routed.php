<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Service;

// [imports]
use Ra5k\Salud\{Service, Fork, Request, Response, Indicator, System};

/**
 *
 *
 *
 */
abstract class Routed implements Service
{
    /**
     * @var Fork
     */
    private $fork;

    /**
     * @param Fork $fork
     */
    public function __construct(Fork $fork)
    {
        $this->fork = $fork;
    }

    /**
     * @param Request $request
     * @return Indicator
     */
    protected function route(Request $request): Indicator
    {
        $context = new System\Context();
        return $this->fork->route($request, ['path' => $context->suffix()]);        
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $target = $this->route($request);
        if ($target->isMatch()) {
            $response = $target->response();
        } else {
            $response = new Response\Zero;
        }
        return $response;
    }

}


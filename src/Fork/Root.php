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
use Ra5k\Salud\{Fork, Request, Indicator, Sapi};


/**
 *
 *
 *
 */
final class Root implements Fork
{
    /**
     * @var Fork
     */
    private $main;

    /**
     * @var Sapi
     */
    private $sapi;

    /**
     * @param Fork $main
     */
    public function __construct(Fork $main, Sapi $sapi = null)
    {
        $this->main = $main;
        $this->sapi = $sapi ?? new Sapi\Auto();
    }

    /**
     * @param Request $request
     * @param array $context
     * @return Indicator
     */
    public function route(Request $request, array $context): Indicator
    {
        if (!isset($context['path'])) {
            $context['path'] = $this->sapi->suffix();
        }
        return $this->main->route($request, $context);
    }

}

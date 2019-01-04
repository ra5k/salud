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
use Ra5k\Salud\{Fork, Request, Indicator, System};


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
     * @param Fork $main
     */
    public function __construct(Fork $main)
    {
        $this->main = $main;
    }

    /**
     * @param Request $request
     * @param array $context
     * @return Indicator
     */
    public function route(Request $request, array $context): Indicator
    {
        $sys = new System\Context();
        if (!isset($context['path'])) {
            $context['path'] = $sys->suffix();
        }        
        return $this->main->route($request, $context);
    }

}

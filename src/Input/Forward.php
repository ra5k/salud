<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Input;

// [imports]
use Ra5k\Salud\Input;

/**
 *
 *
 */
interface Forward extends Input
{

    /**
     * @param int $length
     * @return self
     */
    public function next(int $length): self;
    
}

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
interface Random extends Input
{

    /**
     * @param int $offset
     * @param int $length
     * @return self
     */
    public function slice(int $offset, int $length): self;
    
    /**
     * @return int
     */
    public function offset(): int;
    
    
}

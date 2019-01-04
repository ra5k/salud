<?php
/*
 * This file is part of the Ra5k Salut library
 * (c) 2017 GitHub/ra5k
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Standalone;

// [imports]
use ErrorException;

/**
 *
 *
 *
 */
final class Burst
{

    public function __invoke($severity, $message, $file = null, $line = null)
    {
        if (error_reporting() & $severity) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }            
    }
    
}

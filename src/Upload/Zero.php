<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Upload;

// [imports]
use Ra5k\Salud\{Upload, Input};


/**
 *
 *
 *
 */
final class Zero implements Upload
{

    public function error(): int
    {
        return -1;
    }

    public function temp(): string
    {
        return '';
    }
    
    public function moveTo(string $destination): bool
    {
        return false;
    }

    public function name(): string
    {
        return '';
    }

    public function size(): int
    {
        return 0;
    }

    public function stream(): Input\Forward
    {

    }

    public function type(): string
    {
        return 'ZERO';
    }

}

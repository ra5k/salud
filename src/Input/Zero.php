<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Input;

/**
 *
 *
 *
 */
final class Zero implements Forward, Random
{

    public function next(int $length): Forward
    {
        return $this;
    }

    public function data(): string
    {
        return '';
    }

    public function isValid(): bool
    {
        return false;
    }

    public function offset(): int
    {
        return 0;
    }

    public function slice(int $offset, int $length): Random
    {
        return $this;
    }

}

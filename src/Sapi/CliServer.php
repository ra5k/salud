<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Sapi;

/**
 *
 *
 *
 */
final class CliServer extends Base
{

    /**
     * @param string $name
     * @return mixed
     */
    public function param(string $name)
    {
        return filter_input(INPUT_SERVER, $name);
    }

    /**
     * @return string
     */
    public function prefix(): string
    {
        return '';
    }

}

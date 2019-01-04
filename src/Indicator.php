<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud;

/**
 *
 *
 */
interface Indicator
{

    /**
     * @return bool
     */
    public function isMatch(): bool;

    /**
     * @return Response
     */
    public function response(): Response;

}

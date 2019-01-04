<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud;

// [imports]
use Ra5k\Salud\Response\{Status, Cookie, Header};

/**
 *
 *
 */
interface Response
{
    /**
     * Returns the HTTP response status
     * @return Status
     */
    public function status(): Status;

    /**
     * @return Header[]
     */
    public function headers(): array;

    /**
     * @return Cookie[]
     */
    public function cookies(): array;

    /**
     * Sends the response to php://output
     * @return bool
     */
    public function write(): bool;

}

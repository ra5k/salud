<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Exception;

// [imports]
use Ra5k\Salud\Exception;


/**
 * For use with set_error_handler()
 *
 * @see set_error_handler()
 *
 */
final class ErrorException extends \ErrorException implements Exception
{

    /**
     * @param int $severity
     * @param string $message
     * @param string $file
     * @param int $line
     * @return boolean
     * @throws self
     */
    public static function handler($severity, $message, $file = null, $line = null)
    {
        if (error_reporting() & $severity) {
            throw new self($message, 0, $severity, $file, $line);
        }
        return true;
    }


}

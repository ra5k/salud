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
final class Auto extends Wrap
{
    /**
     *
     */
    public function __construct()
    {
        $type = PHP_SAPI;
        if ($type === 'cli-server') {
            $sapi = new CliServer();
        } else if ($type === 'cgi-fcgi') {
            global $_SERVER;
            $sapi = new PhpArray($_SERVER);
        } else {
            $sapi = new PhpInput();
        }
        parent::__construct($sapi);
    }

}

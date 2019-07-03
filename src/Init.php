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
interface Init
{

    /**
     * Returns the configuration parameters
     * @return Param
     */
    public function config(): Param;

    /**
     * Returns the application environment (e.g. development, production)
     * @return string
     */
    public function env(): string;
    
    /**
     * Returns the Log instance
     * 
     * @return Log
     */
    public function log(): Log;
    
    /**
     * Runs the service
     * 
     * @param Service $service
     */
    public function run(Service $service);
    
}

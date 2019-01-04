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
interface Upload
{

    /**
     * Returns the original name of the file on the client machine.
     *
     * $_FILES['userfile']['name']
     *
     * @return string
     */
    public function name(): string;

    /**
     * The mime type of the file, if the browser provided this information. This mime type is
     * however not checked on the PHP side and therefore don't take its value for granted.
     *
     * $_FILES['userfile']['type']
     *
     * @return string
     */
    public function type(): string;

    /**
     * Returns the file size, or -1 of it cannot be obtained.
     *
     * $_FILES['userfile']['size']
     *
     * @return int
     */
    public function size(): int;

    /**
     * Returns the stream object for reading from the file
     *
     * @return Input\Forward
     */
    public function stream(): Input\Forward;

    /**
     * One of PHP's UPLOAD_ERR_XXX constants
     *
     * $_FILES['userfile']['error']
     *
     * @return int
     *
     * @link http://php.net/manual/en/features.file-upload.errors.php
     */
    public function error(): int;

    /**
     * The temporary upload path
     * 
     * $_FILES['userfile']['tmp_name']
     * 
     * @return string
     */
    public function temp(): string;
    
    /**
     * Moves the uploaded file to the given $destination
     *
     * @param string $destination
     *
     * @link http://php.net/manual/en/function.is-uploaded-file.php
     * @link http://php.net/manual/en/function.move-uploaded-file.php
     */
    public function moveTo(string $destination): bool;


}

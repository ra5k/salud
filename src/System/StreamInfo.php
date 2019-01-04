<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\System;

/**
 *
 *
 *
 */
final class StreamInfo
{
    const S_IFMT   = 0170000;
    const S_IFIFO  = 0010000;
    const S_IFCHR  = 0020000;
    const S_IFDIR  = 0040000;
    const S_IFBLK  = 0060000;
    const S_IFREG  = 0100000;
    const S_IFLNK  = 0120000;
    const S_IFSOCK = 0140000;

    /**
     * @var int
     */
    private $mode;

    /**
     * @param array $stat
     */
    public function __construct(array $stat)
    {
        $this->mode = ($stat['mode'] ?? 0) & self::S_IFMT;
    }

    /**
     * @return int
     */
    public function mode()
    {
        return $this->mode;
    }

}

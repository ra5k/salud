<?php

/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Response;

// [imports]
use Ra5k\Salud\Response;

/**
 *
 *
 *
 */
final class Instant implements Response
{
    // [traits]
    use Defaults;

    /**
     *
     */
    const T_TEXT = 'text/plain; charset=UTF-8';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $content
     */
    public function __construct(string $content, string $type = self::T_TEXT)
    {
        $this->content = $content;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function headers(): array
    {
        return [
            new Header('Content-Type', $this->type),
            new Header('Content-Length', strlen($this->content())),
        ];
    }

    /**
     *
     */
    public function write(): bool
    {
        echo $this->content();
        return true;
    }

}

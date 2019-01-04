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
use Ra5k\Salud\{Template, Response};

/**
 *
 *
 *
 */
final class Templated implements Response
{
    // [traits]
    use Defaults;

    /**
     * @var Template
     */
    private $template;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @param Template $template
     */
    public function __construct(Template $template, $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    /**
     *
     */
    public function write(): bool
    {
        $this->template->render($this->data);
        return true;
    }

}

<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Init\Error;


/**
 * Helper class for generating error pages
 *
 *
 */
final class Buffer
{
    /**
     * @var object
     */
    private $storage;

    /**
     * @var string
     */
    private $padding;

    /**
     * @param Buffer $buffer
     * @param string $padding
     */
    public function __construct(string $padding = '')
    {
        $this->padding = $padding;
    }

    /**
     * @param string $content
     * @param array $args
     * @return static
     */
    public function __invoke(string $content, ...$args)
    {
        return $this->write($content, ...$args);
    }

    /**
     * String-cast operator
     * @return string
     */
    public function __toString()
    {
        return $this->content();
    }

    /**
     * @param string $indent
     * @return self
     */
    public function sub(string $indent = '  ')
    {
        $buffer = new self($this->padding . $indent);
        $buffer->storage = $this->storage();
        return $buffer;
    }

    /**
     * @param string $content
     * @param array $args
     * @return $this
     */
    public function write(string $content, ...$args)
    {
        $s = $this->storage();
        $s->content .= $this->padding;
        if ($args) {
            $s->content .= vsprintf($content, $args);
        } else {
            $s->content .= $content;
        }
        $s->content .= "\n";
        return $this;
    }

    /**
     * @return string
     */
    public function content(): string
    {
        return $this->storage()->content;
    }

    /**
     * @return object
     */
    private function storage()
    {
        if ($this->storage === null) {
            $this->storage = (object) ['content' => ''];
        }
        return $this->storage;
    }

}

<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Response;


/**
 *
 *
 *
 */
final class Cookie
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $expire;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var bool
     */
    private $secure;

    /**
     * @var bool
     */
    private $httpOnly;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value = '')
    {
        $this->name = $name;
        $this->value = $value;
        $this->expire = 0;
        $this->path = '';
        $this->domain = '';
        $this->secure = false;
        $this->httpOnly = false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->line();
    }

    /**
     * @return string
     */
    public function line()
    {
        $parts = [
            sprintf("Set-Cookie: %s=%s", urlencode($this->name()), urlencode($this->value()))
        ];
        if ($this->expire > 0) {
            $parts[] = sprintf("expires=%s", date(DATE_COOKIE, $this->expire));
            $parts[] = sprintf('Max-Age=%d', $this->expire - time());
        }
        if ($this->path) {
            $parts[] = sprintf('path=%s', $this->path);
        }
        if ($this->domain) {
            $parts[] = sprintf('domain=%s', $this->domain);
        }
        if ($this->secure) {
            $parts[] = 'secure';
        }
        if ($this->httpOnly) {
            $parts[] = 'HttpOnly';
        }
        return implode("; ", $parts);
    }

    /**
     *
     */
    public function send(): bool
    {
        return setcookie(
            $this->name(), $this->value(),
            $this->expire(), $this->path(), $this->domain(),
            $this->secure(), $this->httpOnly()
        );
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    public function expire(): int
    {
        return $this->expire;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function domain(): string
    {
        return $this->domain;
    }

    public function secure(): bool
    {
        return $this->secure;
    }

    public function httpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @param int $expire
     * @return self
     */
    public function withExpire(int $expire): self
    {
        $cookie = clone $this;
        $cookie->expire = $expire;
        return $cookie;
    }

    /**
     * @param string $path
     * @return self
     */
    public function withPath(string $path): self
    {
        $cookie = clone $this;
        $cookie->path = $path;
        return $cookie;
    }

    /**
     * @param string $domain
     * @return self
     */
    public function withDomain(string $domain): self
    {
        $cookie = clone $this;
        $cookie->domain = $domain;
        return $cookie;
    }

    /**
     * @param bool $secure
     * @return self
     */
    public function withSecure(bool $secure): self
    {
        $cookie = clone $this;
        $cookie->secure = $secure;
        return $cookie;
    }

    /**
     *
     * @param bool $httpOnly
     * @return self
     */
    public function withHttpOnly(bool $httpOnly): self
    {
        $cookie = clone $this;
        $cookie->httpOnly = $httpOnly;
        return $cookie;
    }


}

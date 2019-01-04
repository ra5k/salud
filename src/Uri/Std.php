<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Uri;

// [imports]
use Ra5k\Salud\Uri;
use Ra5k\Salud\Exception\InvalidArgumentException;

/**
 *
 * The standard implementation for an URI
 *
 *
 * @see https://docs.oracle.com/javase/7/docs/api/java/net/URI.html
 * @see http://www.ietf.org/rfc/rfc2396.txt
 *
 */
final class Std extends Dynamic
{
    /**
     * @internal Flag
     */
    const NORMALIZED = 0x01;

    /**
     * @var int
     */
    private $flags = 0;

    /**
     *
     * @param string|array|null $uri
     * @throws InvalidArgumentException
     */
    public function __construct($uri)
    {
        if (is_string($uri)) {
            $this->reverse($uri);
        } else if (is_null($uri)) {
            // pass;
        } else if (is_array($uri)) {
            $this->hydrate($uri);
        } else {
            $type = is_object($uri) ? get_class($uri) : gettype($uri);
            throw new InvalidArgumentException("Argument 1 is of wrong type ($type)");
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return bool
     */
    public function isAbsolute(): bool
    {
        $u = $this->parsed();
        return !empty($u->scheme());
    }

    /**
     * @return bool
     */
    public function isOpaque(): bool
    {
        $u = $this->parsed();
        return $u->scheme()
            && !$u->host()
            && (substr($u->path(), 0, 1) != '/')
            && ($u->path() || $u->query() || $u->fragment());
    }

    /**
     * Normalizes the path
     * @return Uri
     */
    public function normalize(): Uri
    {
        $normalized = $this->parsed();
        if (!($normalized->flags & self::NORMALIZED)) {
            $path = Path::parse($this->path(), $this->pathSep());
            $norm = $path->normalize();
            if ($path !== $norm) {
                $normalized = clone $this;
                $normalized->hydrate(['path' => $norm->toString()]);
                $normalized->uri = null;
            }
            $normalized->flags |= self::NORMALIZED;
        }
        return $normalized;
    }

    /**
     *
     * @param string|Uri $ref
     * @see https://docs.oracle.com/javase/7/docs/api/java/net/URI.html#relativize(java.net.URI)
     */
    public function relativize($ref): Uri
    {
        $uri = ($ref instanceof Uri) ? $ref : new self($ref);
        $path = $this->path();
        //
        if ($this->isOpaque() || $uri->isOpaque()
                || ($this->scheme() != $uri->scheme())
                || $this->authority() != $uri->authority()
                || (substr($uri->path(), 0, strlen($path)) != $path)) {
            $result = $uri;
        } else {
            $path = $this->termPath($path);
            $tail = substr($uri->path(), strlen($path));
            $result = new self(null);
            $result->hydrate([
                'query' => $uri->query(),
                'fragment' => $uri->fragment(),
                'path' => $this->nullIfSame($tail, false)
            ]);
        }
        return $result;
    }

    /**
     * Resolves the given Uri against this Uri
     *
     * @param string|Uri $ref
     * @see https://docs.oracle.com/javase/7/docs/api/java/net/URI.html#resolve(java.net.URI)
     */
    public function resolve($ref): Uri
    {
        $uri = ($ref instanceof Uri) ? $ref : new self($ref);
        //
        if ($uri->isAbsolute() || $this->isOpaque()) {
            // [absolute]
            $resolved = $uri;
        } else if ($uri->fragment()
                && !$uri->path()
                && !$uri->scheme()
                && !$uri->authority()
                && !$uri->query()) {
            // [hash-only]
            $resolved = clone $this;
            $resolved->hydrate(['fragment' => $uri->fragment()]);
        } else {
            // [RFC 2396]
            $resolved = $this->resolveUris($this, $uri);
        }
        //
        return $resolved;
    }

    /**
     * @param Uri $base
     * @param Uri $ref
     * @return self
     */
    private function resolveUris(self $base, Uri $ref)
    {
        $uri = new self([
            'scheme' => $base->scheme(),
            'query' => $ref->query(),
            'fragment' => $ref->fragment()
        ]);
        if ($ref->authority()) {
            $uri->authFrom($ref);
            $path = $ref->path();
        } else {
            $uri->authFrom($base);
            if (substr($ref->path(), 0, 1) == '/') {
                $path = $ref->path();
            } else {
                $head = Path::parse($base->path(), $uri->pathSep());
                $tail = Path::parse($ref->path(), $uri->pathSep());
                $path = $head->join($tail)->toString();
            }
        }
        $uri->hydrate(['path' => $path]);
        return $uri;
    }

    /**
     * @param self $uri
     */
    private function authFrom(Uri $uri)
    {
        $this->hydrate([
            'user' => $uri->user(),
            'pass' => $uri->pass(),
            'host' => $uri->host(),
            'port' => $uri->port()
        ]);
    }

    /**
     * Returns the Path-separator character
     * @return string
     */
    private function pathSep()
    {
        return ($this->scheme() == 'urn') ? ':' : '/';
    }

    /**
     * @param string $path
     * @return string
     */
    private function termPath($path)
    {
        if (substr($path, -1) != '/') {
            $path .= '/';
        }
        return $path;
    }

    /**
     * @param mixed $value
     * @param mixed $test
     * @return mixed
     */
    private function nullIfSame($value, $test)
    {
        return ($value === $test) ? null : $value;
    }

}

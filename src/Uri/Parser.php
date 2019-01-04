<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Uri;

/**
 * An extended URI parser
 *
 *
 */
final class Parser
{

   /**
     * @var string
     */
    private $uri;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $length;

    /**
     * @var array
     */
    private $parts;

    /**
     * @var array
     */
    private $errors;


    /**
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->uri = (string) $uri;
        $this->offset = 0;
        $this->length = strlen($this->uri);
        $this->errors = [];
    }

    /**
     * Parses the given URI and returns an array with the same possible keys as parse_url()
     *
     * @return array
     * @see parse_url()
     */
    public function parse(): array
    {
        if (null === $this->parts) {
            $this->parts = [];
            $this->parseScheme();
            $this->parseHost();
            $this->parsePort();
            $this->parsePath();
            $this->parseQuery();
            $this->parseFragment();
        }
        return $this->parts;
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Parses the scheme
     */
    private function parseScheme()
    {
        $match = null;
        $pattern = '|^([A-Za-z][A-Za-z0-9\.\+\-]*):|';
        if (preg_match($pattern, $this->uri, $match)) {
            $this->parts['scheme'] = $match[1];
            $this->offset += strlen($match[0]);
        }
    }

    /**
     *
     */
    private function parseHost()
    {
        $stop = null;
        $match = null;
        $flags = PREG_OFFSET_CAPTURE;
        //
        if (substr($this->uri, $this->offset, 2) == '//') {
            $this->offset += 2;
            $at = strpos($this->uri, '@', $this->offset);
            if ($at !== false) {
                $this->parseAuth($at);
            }
            if (substr($this->uri, $this->offset, 1) == '[') {
                $this->parts['host'] = $this->readWrapped(1);
            } elseif (preg_match('|[/?#;:]|', $this->uri, $match, $flags, $this->offset)) {
                $stop = $match[0][0];
                $term = $match[0][1];
                $this->parts['host'] = substr($this->uri, $this->offset, $term - $this->offset);
                $this->offset = $term;
            } else {
                $this->parts['host'] = substr($this->uri, $this->offset);
                $this->offset = $this->length;
            }
        }
    }

    /**
     *
     * @param string $until
     */
    private function parseAuth($until)
    {
        $auth = substr($this->uri, $this->offset, $until - $this->offset);
        $parts = explode(':', $auth, 2);
        $this->parts['user'] = $parts[0];
        if (isset($parts[1])) {
            $this->parts['pass'] = $parts[1];
        }
        $this->offset = $until + 1;
    }

    /**
     *
     * @param int $forward
     * @return type
     */
    private function readWrapped($forward)
    {
        $content = null;
        $term = strpos($this->uri, ']', $this->offset + $forward);
        if ($term !== false) {
            $content = substr($this->uri, $this->offset, $term + 1 - $this->offset);
            $this->offset = $term + 1;
        }
        return $content;
    }

    /**
     *
     */
    private function parsePort()
    {
        if (substr($this->uri, $this->offset, 1) == ':') {
            $this->offset += 1;
            $match = null;
            $flags = PREG_OFFSET_CAPTURE;
            if (preg_match('|[/?#;]|', $this->uri, $match, $flags, $this->offset)) {
                $term = $match[0][1];
                $port = substr($this->uri, $this->offset, $term - $this->offset);
                $this->offset = $term;
            } else {
                $port = substr($this->uri, $this->offset);
                $this->offset = $this->length;
            }
            if (!is_numeric($port)) {
                $this->addError(1, "Port number ($port) must be numeric");
            }
            $this->parts['port'] = (int) $port;
        }
    }

    /**
     *
     */
    private function parsePath()
    {
        $stop = null;
        $match = null;
        $flags = PREG_OFFSET_CAPTURE;
        $path = null;
        if (preg_match('|[?#]|', $this->uri, $match, $flags, $this->offset)) {
            $stop = $match[0][0];
            $term = $match[0][1];
            $path = substr($this->uri, $this->offset, $term - $this->offset);
            $this->offset = $term;
            if ($path) {
                $this->parts['path'] = $path;
            }
        } elseif ($this->offset < $this->length) {
            $this->parts['path'] = (string) substr($this->uri, $this->offset);
            $this->offset = $this->length;
        }
        return $stop;
    }

    /**
     *
     */
    private function parseQuery()
    {
        $first = substr($this->uri, $this->offset, 1);
        if ($first == '?') {
            $this->offset += 1;
            $term = strpos($this->uri, '#', $this->offset);
            if ($term !== false) {
                $query = substr($this->uri, $this->offset, $term - $this->offset);
                $this->offset = $term;
            } else {
                $query = substr($this->uri, $this->offset);
                $this->offset = $this->length;
            }
            $this->parts['query'] = $query;
        }
    }

    /**
     *
     */
    private function parseFragment()
    {
        if (substr($this->uri, $this->offset, 1) == '#') {
            $frag = substr($this->uri, $this->offset + 1);
            $this->offset = $this->length;
            $this->parts['fragment'] = $frag;
        }
    }

    private function addError($code, $message)
    {
        $this->errors[] = ['code' => $code, 'message' => $message];
        return $this;
    }


}

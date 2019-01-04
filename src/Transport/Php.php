<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Transport;


// [imports]
use Ra5k\Salud\{Transport, Response, Response\Cookie, Response\Header, Exception\TypeError};


/**
 *
 *
 *
 */
final class Php implements Transport
{

    /**
     * @param Response $response
     */
    public function sendHeaders(Response $response)
    {
        $this->sendStatus($response);
        $this->sendGeneric($response);
        $this->sendCookies($response);
    }

    /**
     * @param Response $response
     */
    public function sendBody(Response $response)
    {
        $response->write();
    }

    /**
     * Sends the HTTP status line only
     */
    private function sendStatus(Response $response)
    {
        $status = $response->status();
        if ($status->code() != 200) {
            $headline = $status->line();
            header($headline);
        }
    }

    /**
     * Sends generic headers (cookies are handled in a different function)
     * @param Response $response
     */
    private function sendGeneric(Response $response)
    {
        $groups = $this->headerGroups($response->headers());
        foreach ($groups as $group) {
            foreach ($group as $i => $header) {
                /* @var $header Header */
                if ($header->value()) {
                    header($header->line(), ($i == 0) || $header->isUnique());
                } else {
                    header_remove($header->name());
                }
            }
        }
    }

    /**
     *
     * @param Header[] $headers
     */
    private function headerGroups(array $headers)
    {
        $groups = [];
        foreach ($headers as $i => $h) {
            if ($h instanceof Header) {
                $k = $h->key();
                $groups[$k][] = $h;
            } else {
                $type = is_object($h) ? get_class($h) : gettype($h);
                throw new TypeError("Element at $i is not a Header, $type given");
            }
        }
        return $groups;
    }

    /**
     * Sends cookie headers
     */
    private function sendCookies(Response $response)
    {
        foreach ($response->cookies() as $name => $cookie) {
            if (!($cookie instanceof Cookie)) {
                $cookie = new Cookie($name, $cookie);
            }
            $cookie->send();
        }
    }


}

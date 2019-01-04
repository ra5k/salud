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
use Ra5k\Salud\{Transport, Response, Response\Cookie};


/**
 *
 *
 *
 */
final class Dump implements Transport
{
    const EOL = "\r\n";

    /**
     * @param Response $response
     */
    public function sendHeaders(Response $response)
    {
        $this->writeStatus($response);
        $this->writeGeneric($response);
        $this->writeCookies($response);

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
    private function writeStatus(Response $response)
    {
        $status = $response->status();
        if ($status->code() != 200) {
            $this->writeln($status->line());
        }
    }

    /**
     * @param Response $response
     */
    private function writeGeneric(Response $response)
    {
        foreach ($this->headerGroups($response->headers()) as $group) {
            foreach ($group as $header) {
                $this->writeln($header->line());
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
        foreach ($headers as $h) {
            $k = $h->key();
            if ($h->isUnique()) {
                $groups[$k] = [ $h ];
            } else {
                $groups[$k][] = $h;
            }
        }
        return $groups;
    }

    /**
     * Sends cookie headers
     */
    private function writeCookies(Response $response)
    {
        foreach ($response->cookies() as $name => $cookie) {
            if (!($cookie instanceof Cookie)) {
                $cookie = new Cookie($name, $cookie);
            }
            $this->writeln($cookie->toString());
        }
    }

    /**
     * @param string $line
     * @return $this
     */
    private function writeln($line)
    {
        echo $line, self::EOL;
        return $this;
    }

}

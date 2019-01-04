<?php
/* 
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ra5k\Salud\Service;

// [imports]
use Ra5k\Salud\{Service, Request, Response};

/**
 *
 *
 *
 */
final class Dump implements Service
{
    /**
     * @var string
     */
    private $title;
    
    /**
     * @var array
     */
    private $keys;
    
    /**
     * @param string $title
     */
    public function __construct(array $keys, string $title = '')
    {
        $this->keys = $keys;
        $this->title = $title;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
        $body = [];
        foreach ($this->keys as $k) {
            $body[$k] = $request->attribute($k);
        }
        $json = json_encode(['title' => $this->title, 'body' => $body], $flags);
        return new Response\Instant($json, 'application/json');
    }

}


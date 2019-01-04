<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Fork;

// [imports]
use Ra5k\Salud\{Request, Response, Service, Fork, Indicator};

/**
 *
 *
 *
 */
final class Regex implements Fork
{
    // [imports]
    use Flex;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var Fork
     */
    private $forward;

    /**
     * The key (for the regex match) that should be extracted
     * @var string|int
     */
    private $extract;

    /**
     * @param string $pattern
     * @param Fork|Service|Response|array|callable $forward
     */
    public function __construct(string $pattern, $forward, $extractKey = 0)
    {
        $this->pattern = $pattern;
        $this->forward = $this->toNode($forward, 2);
        $this->extract = $extractKey;
    }

    /**
     * @param Request $request
     * @return Indicator
     */
    public function route(Request $request, array $context): Indicator
    {
        $subject = $context['path'] ?? $request->uri()->path();
        $matches = null;
        //
        if (preg_match($this->pattern, $subject, $matches, PREG_OFFSET_CAPTURE)) {
            $request = new Request\WithAttributes($request, $this->params($matches));
            $context = ['path' => $this->remainder($subject, $matches)];
            $target = $this->forward->route($request, $context);
        } else {
            $target = new Indicator\None;
        }
        return $target;
    }

    /**
     * @param array $matches
     * @return array
     */
    private function params(array $matches)
    {
        $params = [];
        foreach ($matches as $k => $m) {
            if (!is_string($k)) {
                continue;
            }
            $params[$k] = $m[0];
        }
        return $params;
    }

    /**
     * @param string $path
     * @param array $matches
     * @return string
     */
    private function remainder($path, $matches)
    {
        $info = $matches[$this->extract];
        $length = strlen($info[0]);
        $offset = $info[1];
        return substr($path, 0, $offset) . substr($path, $offset + $length);
    }

}

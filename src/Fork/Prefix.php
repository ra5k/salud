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
 * Checks for a path prefix
 *
 *
 */
final class Prefix implements Fork
{
    // [imports]
    use Flex;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var Fork
     */
    private $forward;

    /**
     * @param string $prefix
     * @param Fork|Service|Response|array|callable $forward
     */
    public function __construct(string $prefix, $forward)
    {
        $this->prefix = rtrim($prefix, '/');
        $this->forward = $this->toNode($forward);
    }

    /**
     * @param Request $request
     * @return Indicator
     */
    public function route(Request $request, array $context): Indicator
    {
        $subject = rtrim($context['path'] ?? $request->uri()->path(), '/');
        $prefix = $this->prefix;
        //
        if ($this->matches($subject)) {
            $request = new Request\WithAttributes($request, ['prefix' => $prefix]);
            $context = ['path' => substr($subject, strlen($prefix))];
            $target = $this->forward->route($request, $context);
        } else {
            $target = new Indicator\None;
        }
        return $target;
    }

    /**
     * @param string $subject
     * @return boolean
     */
    private function matches($subject)
    {
        $matches = false;
        $prefix = $this->prefix;
        $length = strlen($prefix);
        //
        if ($length == 0) {
            $matches = ($subject == '');
        } else if (substr($subject, 0, $length) == $prefix) {
            if ($length == strlen($subject) || substr($subject, $length, 1) == '/') {
                $matches = true;
            } else {
                $matches = false;
            }
        }
        return $matches;
    }

}

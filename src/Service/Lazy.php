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
use Ra5k\Salud\{Service, Request, Response, Exception\BadMethodCallException};

/**
 * Creates a service on demand
 *
 *
 */
final class Lazy implements Service
{
    /**
     * @var callback
     */
    private $factory;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var Service
     */
    private $service;

    /**
     * @param callable $constructor
     */
    public function __construct(callable $constructor, array $arguments = [])
    {
        $this->factory = $constructor;
        $this->arguments = $arguments;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @throws BadMethodCallException
     */
    public function handle(Request $request): Response
    {
        if ($this->service === null) {
            $factory = $this->factory;
            $arguments = $this->arguments;
            $service = $factory(...$arguments);
            if (!($service instanceof Service)) {
                $type = is_object($service) ? get_class($service) : gettype($service);
                throw new BadMethodCallException("Callback returned invalid type ($type)");
            }
            $this->service = $service;
        }
        return $this->service->handle($request);
    }

}

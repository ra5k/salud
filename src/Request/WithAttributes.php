<?php
/*
 * This file is part of the Salud library
 * (c) 2019 Ra5k <ra5k@mailbox.org>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ra5k\Salud\Request;

// [imports]
use Ra5k\Salud\{Request};

/**
 *
 *
 *
 */
final class WithAttributes extends Wrap
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * @param Request $origin
     * @param array $attributes
     */
    public function __construct(Request $origin, array $attributes)
    {
        parent::__construct($origin);
        $this->attributes = $attributes;
    }

    /**
     * @return mixed
     */
    public function attribute($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            $attrib = $this->attributes[$name];
        } else {
            $attrib = parent::attribute($name);
        }
        return $attrib;
    }

}

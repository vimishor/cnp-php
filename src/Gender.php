<?php
/**
 * Copyright 2017 Alexandru Guzinschi <alex@gentle.ro>
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */
namespace Vimishor\Cnp;

use Gentle\Embeddable\Embeddable;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
final class Gender extends Embeddable
{
    /**
     * @param string|int $gender Odd for male, even for female.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($gender)
    {
        $gender = is_string($gender) ? (int)$gender : $gender;

        if (!is_int($gender) || !in_array($gender, range(1, 8), false)) {
            throw new \InvalidArgumentException('Unknown gender');
        }

        $this->value = (string)$gender;
    }

    /**
     * @access public
     * @return bool
     */
    public function isMale()
    {
        return (int)$this->value % 2 !== 0;
    }

    /**
     * {@inheritDoc}
     */
    public function equals(Embeddable $object)
    {
        return get_class($object) === 'Vimishor\Cnp\Gender' && $this->value === (string)$object;
    }
}

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
final class Checksum extends Embeddable
{
    /**
     * @param string|int $number
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($number)
    {
        $number = is_int($number) ? (string)$number : $number;

        if (!is_string($number) || !ctype_digit($number) || mb_strlen($number) !== 1) {
            throw new \InvalidArgumentException('Expected a string of length 1');
        }

        $this->value = $number;
    }

    /**
     * {@inheritDoc}
     */
    public function equals(Embeddable $object)
    {
        return get_class($object) === 'Vimishor\Cnp\Checksum' && $this->value === (string)$object;
    }
}

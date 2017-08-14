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
final class Serial extends Embeddable
{
    /**
     * @param string|int $number
     *
     * @throws \InvalidArgumentException
     * @throws \OutOfRangeException
     */
    public function __construct($number)
    {
        if (is_int($number)) {
            $number = (string)$number;
        }

        if (!is_string($number) || !ctype_digit($number)) {
            throw new \InvalidArgumentException(sprintf('Expected string or integer and got %s', gettype($number)));
        }

        if ((int)$number > 999 || (int)$number < 1) {
            throw new \OutOfRangeException(sprintf('Expected a number between 1 and 999 (got %d)', (int)$number));
        }

        $this->value = str_pad((string)$number, 3, 0, STR_PAD_LEFT);
    }

    /**
     * {@inheritDoc}
     */
    public function equals(Embeddable $object)
    {
        return get_class($object) === 'Vimishor\Cnp\Serial' && $this->value === (string)$object;
    }
}

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
final class Skeleton extends Embeddable
{
    public function echoPhrase($string)
    {
        return $string;
    }

    /**
     * {@inheritDoc}
     */
    public function equals(Embeddable $object)
    {
        // TODO: Implement equals() method.
    }
}

<?php
/**
 * Copyright 2017 Alexandru Guzinschi <alex@gentle.ro>
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */
namespace Vimishor\Cnp\Test;

use Vimishor\Cnp\Gender;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
class GenderTest extends TestCase
{
    /**
     * @param string|int $value
     * @return void
     *
     * @dataProvider validGenderProvider
     */
    public function testInstantiateSuccess($value)
    {
        $gender = new Gender($value);
        $this->assertInstanceOf(Gender::class, $gender);
    }

    /**
     * @param string|int $value
     *
     * @dataProvider invalidGenderProvider
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiateError($value)
    {
        new Gender($value);
    }

    /**
     * @param string|int $value
     *
     * @dataProvider validGenderProvider
     */
    public function testIsMale($value)
    {
        $female = [2, 4, 6, 8];
        $gender = new Gender($value);

        if (in_array((int)$gender->__toString(), $female, false)) {
            $this->assertFalse($gender->isMale());
        } else {
            $this->assertTrue($gender->isMale());
        }
    }

    /**
     * @param string|int $value
     *
     * @dataProvider validGenderProvider
     */
    public function testEqualityByValue($value)
    {
        $gender1 = new Gender($value);
        $gender2 = new Gender($value);

        $this->assertTrue($gender1->equals($gender2));
        $this->assertNotSame($gender1, $gender2);
    }

    public function validGenderProvider()
    {
        return [
            [1], [02], ['02'], ['07'], ['05'], [8]
        ];
    }

    public function invalidGenderProvider()
    {
        return [
            [new \stdClass()], ['0,1'], ['32,43'], ['21.4'], ['string'], ['60'], [60], [9], [[1]], [[02]]
        ];
    }
}

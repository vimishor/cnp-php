<?php
/**
 * Copyright 2017 Alexandru Guzinschi <alex@gentle.ro>
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */
namespace Vimishor\Cnp\Test;

use Vimishor\Cnp\County;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
class CountyTest extends TestCase
{
    /**
     * @param string|int $value
     * @return void
     *
     * @dataProvider validCountyProvider
     */
    public function testInstantiateSuccess($value)
    {
        $county = new County($value);
        $this->assertInstanceOf(County::class, $county);
    }

    /**
     * value should always have a leading zero
     *
     * @param string|int $value
     * @return void
     *
     * @dataProvider validCountyProvider
     */
    public function testValueNormalization($value)
    {
        $county = new County($value);
        $this->assertSame(2, mb_strlen((string)$county));
        $this->assertEquals(str_pad((string)$value, 2, 0, STR_PAD_LEFT), $county->__toString());
    }

    /**
     * @param string|int $value
     *
     * @dataProvider invalidCountyProvider
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiateError($value)
    {
        new County($value);
    }

    /**
     * @param string|int $value
     *
     * @dataProvider validCountyProvider
     */
    public function testEqualityByValue($value)
    {
        $county1 = new County($value);
        $county2 = new County($value);

        $this->assertTrue($county1->equals($county2));
        $this->assertNotSame($county1, $county2);
    }

    /**
     * @param string|int $value
     *
     * @dataProvider validCountyProvider
     */
    public function testEqualityByCountyName($value)
    {
        $county1 = new County($value);
        $county2 = new County($value);

        $this->assertEquals($county1->getName(), $county2->getName());
        $this->assertNotSame($county1, $county2);
    }

    public function validCountyProvider()
    {
        return [
            [1], [02], ['02'], ['47'], [36], ['24']
        ];
    }

    public function invalidCountyProvider()
    {
        return [
            [new \stdClass()], ['0,1'], ['32,43'], ['21.4'], ['string'], ['60'], [60], [[1]], [[02]]
        ];
    }
}

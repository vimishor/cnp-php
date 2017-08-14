<?php
/**
 * Copyright 2017 Alexandru Guzinschi <alex@gentle.ro>
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */
namespace Vimishor\Cnp\Test;

use Vimishor\Cnp\Checksum;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
class ChecksumTest extends TestCase
{
    /**
     * @param string|int $value
     * @return void
     *
     * @dataProvider validChecksumProvider
     */
    public function testInstantiateSuccess($value)
    {
        $number = new Checksum($value);
        $this->assertInstanceOf(Checksum::class, $number);
    }

    /**
     * @param string|int $value
     * @return void
     *
     * @dataProvider invalidChecksumProvider
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiateError($value)
    {
        new Checksum($value);
    }

    /**
     * @param string|int $value
     *
     * @dataProvider validChecksumProvider
     */
    public function testEqualityByValue($value)
    {
        $number1 = new Checksum($value);
        $number2 = new Checksum($value);

        $this->assertTrue($number1->equals($number2));
        $this->assertNotSame($number1, $number2);
    }

    public function validChecksumProvider()
    {
        return [
            [1], [2], [01], [02], ['1'], ['2']
        ];
    }

    public function invalidChecksumProvider()
    {
        return [
            [new \stdClass()], ['0,1'], ['32,43'], ['21.4'], ['string'], ['21'], ['21'], ['01'], ['02'], [[1]], [[02]]
        ];
    }
}

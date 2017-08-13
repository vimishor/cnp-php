<?php
/**
 * Copyright 2017 Alexandru Guzinschi <alex@gentle.ro>
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */
namespace Vimishor\Cnp\Test;

use Vimishor\Cnp\Serial;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
class SerialTest extends TestCase
{
    /**
     * @param string|int $value
     * @return void
     *
     * @dataProvider validSerialProvider
     */
    public function testInstantiateSuccess($value)
    {
        $serial = new Serial($value);
        $this->assertInstanceOf(Serial::class, $serial);
    }

    /**
     * @param string|int $value
     *
     * @dataProvider invalidSerialProvider
     * @expectedException \InvalidArgumentException
     */
    public function testInstantiateError($value)
    {
        new Serial($value);
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testRangeError()
    {
        new Serial(1000);
        new Serial(0);
    }

    /**
     * @param string|int $value
     *
     * @dataProvider validSerialProvider
     */
    public function testEqualityByValue($value)
    {
        $serial1 = new Serial($value);
        $serial2 = new Serial($value);

        $this->assertTrue($serial1->equals($serial2));
        $this->assertNotSame($serial1, $serial2);
    }

    public function validSerialProvider()
    {
        return [
            [1], [02], [004], [42], ['042'], ['001'], ['02'], ['004'], ['42'], ['842'], [999]
        ];
    }

    public function invalidSerialProvider()
    {
        return [
            [new \stdClass()], ['0,1'], ['32,43'], ['21.4'], ['string'], [[1]], [[02]]
        ];
    }
}

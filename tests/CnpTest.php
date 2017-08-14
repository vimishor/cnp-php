<?php
/**
 * Copyright 2017 Alexandru Guzinschi <alex@gentle.ro>
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */
namespace Vimishor\Cnp\Test;

use Gentle\Embeddable\Date;
use Vimishor\Cnp\Checksum;
use Vimishor\Cnp\Cnp;
use Vimishor\Cnp\County;
use Vimishor\Cnp\Gender;
use Vimishor\Cnp\Serial;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
class CnpTest extends TestCase
{
    /**
     * @param string|int $value
     *
     * @dataProvider invalidCnpProvider
     * @expectedException \Vimishor\Cnp\Exception\InvalidCnpException
     */
    public function testInvalidCnp($value)
    {
        Cnp::fromString($value);
    }

    /**
     * @dataProvider validCnpProvider
     */
    public function testResident($gender, $date, $county, $serial, $controlNumber)
    {
        $cnp = new Cnp($gender, $date, $county, $serial, $controlNumber);

        if (in_array((string)$gender, ['7', '8'], true)) {
            $this->assertTrue($cnp->isResident());
        }
    }

    /**
     * @dataProvider validCnpProvider
     */
    public function testEqualityByValue($gender, $date, $county, $serial, $controlNumber)
    {
        $cnp1 = new Cnp($gender, $date, $county, $serial, $controlNumber);
        $cnp2 = new Cnp($gender, $date, $county, $serial, $controlNumber);

        $this->assertTrue($cnp1->equals($cnp2));
        $this->assertNotSame($cnp1, $cnp2);
    }

    public function validCnpProvider()
    {
        return [
            // gender=f, resident=no, born=19-12-1979, county=Bucuresti/Sectorul 7
            [new Gender(2), Date::fromString('1979-12-19T19:10:23+00:00'), new County(47), new Serial(003), new Checksum(4)],

            // gender=f, resident=no, born=21-02-1864, county=Bucuresti/Sectorul 8
            [new Gender(4), Date::fromString('1864-02-21T19:10:23+00:00'), new County(48), new Serial('503'), new Checksum(1)],

            // gender=m, resident=no, born=21-02-1964, county=Bucuresti/Sectorul 8
            [new Gender(1), Date::fromString('1964-02-21T19:10:23+00:00'), new County(48), new Serial('003'), new Checksum(5)],

            // gender=m, resident=no, born=14-08-1964, county=Braila
            [new Gender(1), Date::fromString('1964-08-14T19:10:23+00:00'), new County(9), new Serial('003'), new Checksum(9)],

            // gender=f, resident=yes, born=21-04-1981, county=Bucuresti/Sectorul 1
            [new Gender(8), Date::fromString('1981-04-21T19:10:23+00:00'), new County(41), new Serial('003'), new Checksum(2)],

            // gender=m, resident=yes, born=23-04-1981, county=Bucuresti/Sectorul 1
            [new Gender(7), Date::fromString('1981-04-23T19:10:23+00:00'), new County(42), new Serial('003'), new Checksum(3)],

            // gender=m, resident=no, born=04-11-2003, county=Arad
            [new Gender(5), Date::fromString('2003-11-04T19:10:23+00:00'), new County('02'), new Serial('415'), new Checksum(9)],

            // gender=m, resident=no, born=24-04-2022, county=Covasna
            [new Gender(5), Date::fromString('2022-04-24T19:10:23+00:00'), new County(14), new Serial('415'), new Checksum(3)],
        ];
    }

    public function invalidCnpProvider()
    {
        return [
            ['484092141038'],   // to short
            ['48409214100381'], // to long
            ['5020315254123'],  // invalid checksum
            ['9020315254129'],  // unknown gender
            ['2920421473211'],  // district 7 after December 19, 1979
            ['7920421483218'],  // resident with district 8
            ['3920317601119'],  // unknown county
            ['6160334271111'],  // invalid day: 34
            ['2940231391116'],  // invalid day: Feb, 31
            ['1921116170008'],  // invalid serial
        ];
    }
}

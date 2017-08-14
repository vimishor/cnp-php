<?php
/**
 * Copyright 2017 Alexandru Guzinschi <alex@gentle.ro>
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */
namespace Vimishor\Cnp;

use Gentle\Embeddable\Embeddable;
use Gentle\Embeddable\Date;
use Vimishor\Cnp\Exception\InvalidCnpException;

/**
 * @author Alexandru Guzinschi <alex@gentle.ro>
 */
final class Cnp extends Embeddable
{
    /** @var Gender */
    private $gender;

    /** @var Date */
    private $birthday;

    /** @var County */
    private $county;

    /** @var Serial */
    private $serial;

    /** @var Checksum */
    private $checksum;

    /**
     * Build a {@link CNP} instance from a CNP number.
     *
     * @static
     * @access public
     * @param  string $number A CNP number.
     * @return self
     *
     * @throws InvalidCnpException
     */
    public static function fromString($number)
    {
        if (13 !== mb_strlen($number)) {
            throw new InvalidCnpException(
                'Invalid CNP length.',
                0,
                new \LengthException('Expected a string number of 13 characters.')
            );
        }

        try {
            return new self(
                new Gender(self::getValue($number, 0)),
                new Date(
                    new Date\Year('19'.self::getValue($number, 1).self::getValue($number, 2)),
                    new Date\Month(self::getValue($number, 3).self::getValue($number, 4)),
                    new Date\Day(self::getValue($number, 5).self::getValue($number, 6))
                ),
                new County(self::getValue($number, 7).self::getValue($number, 8)),
                new Serial(self::getValue($number, 9).self::getValue($number, 10).self::getValue($number, 11)),
                new Checksum(self::getValue($number, 12))
            );
        } catch (\Exception $e) {
            throw new InvalidCnpException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param Gender   $gender
     * @param Date     $birthday
     * @param County   $county
     * @param Serial   $serial
     * @param Checksum $checksum
     *
     * @throws InvalidCnpException
     */
    public function __construct(Gender $gender, Date $birthday, County $county, Serial $serial, Checksum $checksum)
    {
        $this->gender = $gender;
        $this->serial = $serial;
        $this->checksum = $checksum;

        try {
            $this->setBirthday($birthday);
            $this->setCounty($county);
        } catch (\DomainException $e) {
            throw new InvalidCnpException('Provided number is not a valid CNP.', $e->getCode(), $e);
        }

        $this->validate((string)$this);
    }

    /**
     * @access public
     * @return Gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @access public
     * @return Date
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @access public
     * @return County
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @access public
     * @return Serial
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @access public
     * @return Checksum
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * @access public
     * @return bool
     */
    public function isResident()
    {
        return in_array(sprintf('%s', $this->getGender()), ['7', '8'], false);
    }

    /**
     * {@inheritDoc}
     */
    public function equals(Embeddable $object)
    {
        return get_class($object) === 'Vimishor\Cnp\Cnp' && (string)$this === (string)$object;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return (string)$this->getGender().
            (string)$this->getBirthday()->asDateTime()->format('ymd').
            (string)$this->getCounty().
            (string)$this->getSerial().
            (string)$this->getChecksum();
    }

    /**
     * Rebuild a date object with the year changed.
     *
     * Year prefix is changed according to gender.
     *
     * @access private
     * @param  Date $birthday
     * @return $this
     */
    private function setBirthday(Date $birthday)
    {
        $year   = (string)$birthday->getYear();
        $prefix = $this->getYearPrefix($this->getGender());

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $date = new Date(
            new Date\Year($prefix . self::getValue($year, 2) . self::getValue($year, 3)),
            new Date\Month((string)$birthday->getMonth()),
            new Date\Day((string)$birthday->getDay())
        );

        $this->birthday = $date;

        return $this;
    }

    /**
     * @access private
     * @param  County $county
     * @return $this
     *
     * @throws \DomainException
     */
    private function setCounty(County $county)
    {
        // Allow usage of Bucharest's district 7 and 8 only for dates prior to December 19, 1979.
        if (in_array((string)$county, ['47', '48'], false) &&
            strtotime($this->getBirthday()) > strtotime(Date::fromString('1979-12-19T23:59:23+00:00'))
        ) {
            $district = (string)$county;
            throw new \DomainException(
                sprintf("Bucharest's district %d doesn't exist anymore since 1979.", $district[1])
            );
        }

        $this->county = $county;

        return $this;
    }

    /**
     * Extract element from string at given position.
     *
     * @access private
     * @param  string $from
     * @param  int    $position
     * @return string
     */
    private static function getValue($from, $position)
    {
        $arr = str_split($from);

        return (string)$arr[$position];
    }

    /**
     * @access private
     * @param  Gender $gender
     * @return int
     */
    private function getYearPrefix(Gender $gender)
    {
        $prefix = 0;

        switch ((int)sprintf('%s', $gender)) {
            case 1:
            case 2:
            case 7:
            case 8:
                $prefix = 19;
                break;
            case 3:
            case 4:
                $prefix = 18;
                break;
            case 5:
            case 6:
                $prefix = 20;
                break;
        } // @codeCoverageIgnore

        return $prefix;
    }

    /**
     * Calculate checksum and compare with the one provided in order to validate
     *
     * @access private
     * @param  string $cnp
     * @return void
     *
     * @throws \Vimishor\Cnp\Exception\InvalidCnpException
     */
    private function validate($cnp)
    {
        // calculate control number and compare in order to validate
        if ((int)sprintf('%s', $this->getChecksum()) !== (int)$this->calculateChecksum($cnp)) {
            throw new InvalidCnpException('Provided number is not a valid CNP.');
        }
    }

    /**
     * @access private
     * @param  string $cnp
     * @return int
     */
    private function calculateChecksum($cnp)
    {
        $sum    = 0;

        for ($i = 0; $i <= 11; ++$i) {
            $sum += (int) self::getValue((string) $cnp, $i) * (int) self::getValue('279146358279', $i);
        }

        $sum %= 11;

        // Rule says that 10 should be interpreted as 1
        return (10 === $sum) ? 1 : $sum;
    }
}

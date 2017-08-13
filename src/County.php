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
final class County extends Embeddable
{
    /** @var array */
    private static $counties = [
        '01' => 'Alba',
        '02' => 'Arad',
        '03' => 'Arges',
        '04' => 'Bacau',
        '05' => 'Bihor',
        '06' => 'Bistrita-Nasaud',
        '07' => 'Botosani',
        '08' => 'Brasov',
        '09' => 'Braila',
        '10' => 'Buzau',
        '11' => 'Caras-Severin',
        '12' => 'Cluj',
        '13' => 'Constanta',
        '14' => 'Covasna',
        '15' => 'Dambovita',
        '16' => 'Dolj',
        '17' => 'Galati',
        '18' => 'Gorj',
        '19' => 'Harghita',
        '20' => 'Hunedoara',
        '21' => 'Ialomita',
        '22' => 'Iasi',
        '23' => 'Ilfov',
        '24' => 'Maramures',
        '25' => 'Mehedinti',
        '26' => 'Mures',
        '27' => 'Neamt',
        '28' => 'Olt',
        '29' => 'Prahova',
        '30' => 'Satu Mare',
        '31' => 'Salaj',
        '32' => 'Sibiu',
        '33' => 'Suceava',
        '34' => 'Teleorman',
        '35' => 'Timis',
        '36' => 'Tulcea',
        '37' => 'Vaslui',
        '38' => 'Valcea',
        '39' => 'Vrancea',
        '40' => 'Bucuresti',
        '41' => 'Bucuresti/Sectorul 1',
        '42' => 'Bucuresti/Sectorul 2',
        '43' => 'Bucuresti/Sectorul 3', // was merged with district 2 in 1979 => district 2
        '44' => 'Bucuresti/Sectorul 4', // district 3 since 1979
        '45' => 'Bucuresti/Sectorul 5', // district 4 since 1979
        '46' => 'Bucuresti/Sectorul 6', // district 5 since 1979
        '47' => 'Bucuresti/Sectorul 7', // district 6 since 1979
        '48' => 'Bucuresti/Sectorul 8', // was merged with district 1 in 1979 => district 1
        '51' => 'Calarasi',
        '52' => 'Giurgiu'
    ];

    /**
     * @param string|int $county
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($county)
    {
        if (is_int($county)) {
            $county = (string)$county;
        }

        if (!is_string($county) && !ctype_digit($county)) {
            throw new \InvalidArgumentException(sprintf('Expected string or integer and got %s', gettype($county)));
        }

        $county = str_pad((string)$county, 2, 0, STR_PAD_LEFT);

        if (!array_key_exists($county, self::$counties)) {
            throw new \InvalidArgumentException(sprintf('%s is not a known county.', $county));
        }

        $this->value = $county;
    }

    /**
     * @access public
     * @return string
     */
    public function getName()
    {
        return self::$counties[$this->value];
    }

    /**
     * {@inheritDoc}
     */
    public function equals(Embeddable $object)
    {
        return get_class($object) === 'Vimishor\Cnp\County' && $this->value === (string)$object;
    }
}

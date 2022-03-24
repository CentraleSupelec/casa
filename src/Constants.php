<?php

namespace App;

final class Constants
{
    /**
     * @return string[]
     */
    public static function getHousingTypes(): array
    {
        return [
            'T1' => 'T1',
            'T2' => 'T2',
            'T3' => 'T3',
        ];
    }

    /**
     * @return string[]
     */
    public static function getHousingLivingModes(): array
    {
        return [
            'Entier' => 'entier',
            'Chez l\'habitant' => 'habitant',
        ];
    }

    /**
     * @return string[]
     */
    public static function getHousingOccupationModes(): array
    {
        return [
            'Seul' => 'seul',
            'Couple' => 'couple',
            'Colocation' => 'colocation',
        ];
    }

    /**
     * @return string[]
     */
    public static function getAddressCountries(): array
    {
        return ['FRANCE' => 'france'];
    }
}

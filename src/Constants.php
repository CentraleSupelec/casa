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
            'housing.type.studio' => 'Studio',
            'housing.type.t1' => 'T1',
            'housing.type.t1bis' => 'T1Bis',
            'housing.type.t2' => 'T2',
            'housing.type.t3' => 'T3',
            'housing.type.t4' => 'T4',
        ];
    }

    /**
     * @return string[]
     */
    public static function getHousingLivingModes(): array
    {
        return [
            'housing.living_mode.full' => 'full',
            'housing.living_mode.homestay' => 'homestay',
        ];
    }

    /**
     * @return string[]
     */
    public static function getHousingOccupationModes(): array
    {
        return [
            'housing.occupation_mode.alone' => 'alone',
            'housing.occupation_mode.couple' => 'couple',
            'housing.occupation_mode.share' => 'share',
        ];
    }

    /**
     * @return string[]
     */
    public static function getAddressCountries(): array
    {
        return ['housing_group.country.france' => 'france'];
    }

    /**
     * @return string[]
     */
    public static function getPointsOfInterestCategories(): array
    {
        return [
            'housing_group.point_of_interest_category.store' => 'store',
            'housing_group.point_of_interest_category.transportation' => 'transportation',
        ];
    }
}

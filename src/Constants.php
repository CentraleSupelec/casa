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
            'housing.type.t1' => 't1',
            'housing.type.t2' => 't2',
            'housing.type.t3' => 't3',
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

<?php

namespace App;

final class Constants
{
    public const NON_VERIFIED_ACCOUNT_ERROR_CODE = 4031;

    public const APP_EMAIL_ADDRESS = 'no-reply@universite-paris-saclay.fr';

    public const HOUSING_REQUEST_DEFAULT_EMAIL = 'housing-service@universite-paris-saclay.fr';

    public const HOUSING_REQUEST_ARCHIVE_EMAIL = 'housing-service-archive@universite-paris-saclay.fr';

    /**
     * @return string[]
     */
    public static function getHousingTypes(): array
    {
        return [
            'housing.type.studio' => 'studio',
            'housing.type.t1' => 't1',
            'housing.type.t1bis' => 't1bis',
            'housing.type.t2' => 't2',
            'housing.type.t3' => 't3',
            'housing.type.t4' => 't4',
            'housing.type.t5' => 't5',
            'housing.type.t6' => 't6',
            'housing.type.t7' => 't7',
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

    public static function getPlatformEmailAddress(): string
    {
        return 'psuh@paris-saclay.com';
    }

    /**
     * @return string[]
     */
    public static function getRoles(): array
    {
        return [
            'Etudiant' => 'ROLE_STUDENT',
            'Administrateur' => 'ROLE_ADMIN',
        ];
    }

    public static function getCampus(): array
    {
        $campus = [
            'Plateau de Saclay',
            'Évry',
            'Brétigny-sur-Orge',
            'Juvisy-sur-Orge',
            'Montigny-le-Bretonneux',
            'Guyancourt',
            'Vélizy-Villacoublay',
            'Mantes-la-Ville',
            'Rambouillet',
            'Versailles',
            'Mantes-la-Jolie',
            'Boulogne-Billancourt',
            'Garches',
            'Suresnes',
            'Les Mureaux',
            'Poissy',
            'Palaiseau',
            'Orsay vallée',
            'Le Kremlin-Bicêtre',
            'Saint-Maurice',
            'Longjumeau',
            'Clamart',
            'Etampes',
            'Épinay sur Orge',
            'Villejuif',
            'Sceaux',
            'Cachan',
        ];

        return array_combine($campus, $campus);
    }
}

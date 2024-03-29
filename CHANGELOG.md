# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.8]

- Static Content fixes

## [1.2.7]

### Added

- Static content
- 3 new Campus in constant

## [1.2.6] - 2022-11-22 on INT

### Added

- Admin
    - Can now set display order for guarantor, lease_type, occupation_mode and stay_duration

- Search 
    - Criterion are now stored in a session cookie
    - This fixes keeping search crit after adding a bookmark / create account / validate / login 

- Housing detail
    - add return to List

### Fixed

- Housing Group
    - housing group remove, removes too much
    - Country label is now displayed in french 
    - Postal code moved before city

- Housing
    - help texts reworked
    - Fix crash with URL longer than 255 
    - Limit amounts to positive only values
    - crash when trying to save picture with no file.
    - Geoapify credits sentence reworked

- Global    
    - Lessor footer, removed FAQ and contact

- Profile
    - Fix route after editing on student profile
    - Modified birthdate format
    - FIX: birthdate is shown as today when not specified


## [1.2.5.1]

- Env variable for Matomo script ( change value in helm env-vars before deployment )

## [1.2.4.1-beta]

- Added Favico
- Fix password length message when out of limit is showing {{ limit }}
- Not specified is displayed when housing equipments are not present

Add possible Lease Term to housing
- Sonata admin leaseType
- Lessor admin housing 
- Advanced search filter
- Housing detail display

Lessor Admin
- filter housing group
- sort housing group 
- Geoapify credits in housing group edit. 

## [1.2.3-beta] 27/10/2022

- Fontawesome deployed

## [1.2.1-beta] 26/10/2022

Lessor Admin. (/lessor/admin)
- Admin lessor users (from sonata super admin)
- lessor can now login to their admin page 
- lessor can reset password
- CRUD for Housing grou
- CRUD for Housing

Housing - New housing characteristics
- APL 
- Occupation mode is now multi value ( and can be extended in admin ) 
- stay durations ( list admin in sonata fr/en )

new housing group characteristic 
- Guarantor ( list admin in sonata fr/en )

Fixes. (lot1 user feeback)
- home page search is simple is now limited to simple criteria
- city is now an autocomplete list ( cities sorted ASC by name )
- Advanced search on housing list
- Footer Urgent link moved to profile
- Contact form rework
- added some qualification to the list. (split 1 in 2)
- information is now displayed to student when important profile data are not specified.
- Housing List map pin and tooltip with price, card rework, border on hover

General 
- Typo
- Global margins ( container-fluid -> container )
- Housing detail rework

## [1.0.0] - 13/06/2022
- Display offers list and detail pages
- Enable users to filter offers on rent, surface area, city or reduced mobility criteria
- Display offers criteria (social scholarship or school partnership)
- Handle student accounts with authentication on the platform (register, verify email, log in, reset password, etc.)
- Enable students to fill their profile, and save offers in bookmarks
- Handle matching between student profil and offers criteria
- Enable students to send generic and emergency request by email through the platform
- Add a CRUD admin platform to manage all data
- Add administrator users who can access the admin platform


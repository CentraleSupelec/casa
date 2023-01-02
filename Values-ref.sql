-- Active: 1666251074584@@localhsot@5432@psuh-db

DELETE FROM occupation_mode;

INSERT INTO
    occupation_mode (id, label_fr, label_en)
VALUES (1, 'à ocuper seul', 'alone');

INSERT INTO
    occupation_mode (id, label_fr, label_en)
VALUES (
        2,
        'En couple sans enfant',
        'couple no children'
    );

INSERT INTO
    occupation_mode (id, label_fr, label_en)
VALUES (3, 'Famille monoparentale', '');

INSERT INTO
    occupation_mode (id, label_fr, label_en)
VALUES (
        4,
        'En couple avec enfant',
        'couple with children'
    );

INSERT INTO
    occupation_mode (id, label_fr, label_en)
VALUES (
        6,
        'Colocation non mixte',
        'Share ..?'
    );

ALTER SEQUENCE occupation_mode_id_seq RESTART WITH 7;

DELETE FROM lease_type;

INSERT INTO
    lease_type (id, label_fr, label_en)
VALUES (
        1,
        'Meublé avec tacite reconduction',
        'furnished with automatic renewal'
    );

INSERT INTO
    lease_type (id, label_fr, label_en)
VALUES (
        2,
        'Meublé sans tacite reconduction',
        'furnished without automatic renewal'
    );

INSERT INTO
    lease_type (id, label_fr, label_en)
VALUES (
        3,
        'Non meublé avec tacite reconduction',
        'not furnished with automatic renewal'
    );

INSERT INTO
    lease_type (id, label_fr, label_en)
VALUES (
        4,
        'Non meublé sans tacite reconducation',
        'not furnished and no automatic renewal'
    );

ALTER SEQUENCE lease_type_id_seq RESTART WITH 5;

delete from guarantor;

INSERT INTO
    guarantor (id, label_fr, label_en)
VALUES (
        1,
        'Pas d''exigence particulière',
        'No special requirement'
    );

INSERT INTO
    guarantor (id, label_fr, label_en)
VALUES (
        2,
        'Garant français',
        'French guarantor'
    );

INSERT INTO
    guarantor (id, label_fr, label_en)
VALUES (
        3,
        'Garant en France',
        'Guarantor in France'
    );

INSERT INTO
    guarantor (id, label_fr, label_en)
VALUES (
        4,
        'Garant ayan un compte bancaire en France',
        'Guarantor with a french bank account'
    );

ALTER SEQUENCE guarantor_id_seq RESTART WITH 5;

delete from stay_duration;

INSERT INTO
    stay_duration (id, label_fr, label_en)
VALUES (
        1,
        'Long séjour : supérieur à 1 an',
        'Long term lease : more than one year'
    );

INSERT INTO
    stay_duration (id, label_fr, label_en)
VALUES (
        2,
        'Moyen séjour : entre 6 mois et 1 an',
        'Mid term lease : between 6 months and 1 year'
    );

INSERT INTO
    stay_duration (id, label_fr, label_en)
VALUES (
        3,
        'Court séjour : entre 1 mois et 6 mois',
        'Short term lease : between 1 and 6 months'
    );

INSERT INTO
    stay_duration (id, label_fr, label_en)
VALUES (
        4,
        'Séjour temporaire : inférieur à 1 mois',
        'Temporay lease : less than 1 month'
    );

ALTER SEQUENCE stay_duration_id_seq RESTART WITH 5;
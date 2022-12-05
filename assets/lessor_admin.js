// Photo Collection handling.
const $wrapper_photo = $('.js-photo-wrapper');

$wrapper_photo.on('click', '.js-remove-photo', function (e) {
    e.preventDefault();
    $(this).closest('.js-photo-item').remove();
});

$wrapper_photo.on('click', '.js-add-photo', function (e) {
    e.preventDefault();
    var prototype = $wrapper_photo.data('prototype');
    var index = $wrapper_photo.data('index');

    var newForm = prototype.replace(/__name__/g, index);
    $wrapper_photo.data('index', index + 1);

    $(this).before(newForm);
});

// School Criteria Collection handling.
const $wrapper_school_criteria = $('.js-school-criteria-wrapper');

$wrapper_school_criteria.on(
    'click',
    '.js-remove-school-criteria',
    function (e) {
        e.preventDefault();
        $(this).closest('.js-school-criteria-item').remove();
    }
);

$wrapper_school_criteria.on('click', '.js-add-school-criteria', function (e) {
    e.preventDefault();
    var prototype = $wrapper_school_criteria.data('prototype');
    var index = $wrapper_school_criteria.data('index');

    var newForm = prototype.replace(/__name__/g, index);
    $wrapper_school_criteria.data('index', index + 1);

    $(this).before(newForm);
});

// SocialScholarShip Collection handling.
const $wrapper_social_scholarship = $('.js-social-scholarship-wrapper');

$wrapper_social_scholarship.on(
    'click',
    '.js-remove-social-scholarship',
    function (e) {
        e.preventDefault();
        $(this).closest('.js-social-scholarship-item').remove();
    }
);

$wrapper_social_scholarship.on(
    'click',
    '.js-add-social-scholarship',
    function (e) {
        e.preventDefault();
        var prototype = $wrapper_social_scholarship.data('prototype');
        var index = $wrapper_social_scholarship.data('index');

        var newForm = prototype.replace(/__name__/g, index);
        $wrapper_social_scholarship.data('index', index + 1);

        $(this).before(newForm);
    }
);
// housing Group Service Collection handling.
const $wrapper_housing_group_service = $('.js-housing-group-service-wrapper');

$wrapper_housing_group_service.on(
    'click',
    '.js-remove-housing-group-service',
    function (e) {
        e.preventDefault();
        $(this).closest('.js-housing-group-service-item').remove();
    }
);

$wrapper_housing_group_service.on(
    'click',
    '.js-add-housing-group-service',
    function (e) {
        e.preventDefault();
        var prototype = $wrapper_housing_group_service.data('prototype');
        var index = $wrapper_housing_group_service.data('index');

        var newForm = prototype.replace(/__name__/g, index);
        $wrapper_housing_group_service.data('index', index + 1);

        $(this).before(newForm);
    }
);
// housing Group POI Collection handling.
const $wrapper_housing_group_poi = $('.js-housing-group-poi-wrapper');

$wrapper_housing_group_poi.on(
    'click',
    '.js-remove-housing-group-poi',
    function (e) {
        e.preventDefault();
        $(this).closest('.js-housing-group-poi-item').remove();
    }
);

$wrapper_housing_group_poi.on(
    'click',
    '.js-add-housing-group-poi',
    function (e) {
        e.preventDefault();
        var prototype = $wrapper_housing_group_poi.data('prototype');
        var index = $wrapper_housing_group_poi.data('index');

        var newForm = prototype.replace(/__name__/g, index);
        $wrapper_housing_group_poi.data('index', index + 1);

        $(this).before(newForm);
    }
);

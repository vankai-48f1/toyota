// FORCUS INPUT OPEN DROP LIST  

function showDropListCar(elemt, container) {
    jQuery(document).on('focus', elemt, function (e) {
        jQuery(container).addClass('show-view');
    })
}

showDropListCar('.vehicle-model-wrap input.vehicle-model-field', '.list-vehicle-model');
showDropListCar('.vehicle-grade-wrap input.vehicle-grade-field', '.list-vehicle-grade');

// CACULATOR INSURANCE COSTS
jQuery(document).on('click', 'button#btn-insurance-cost', function (e) {
    e.preventDefault();
    var price_value = Number(jQuery('input.vehicle-price-field').val()),
        one_year_cost = Number(jQuery('input.val-one-year-insurance-cost').val()),
        two_year_cost = Number(jQuery('input.val-two-year-insurance-cost').val()),
        three_year_cost = Number(jQuery('input.val-three-year-insurance-cost').val());

    // console.log(customErrorField(model_id));
    var inputElemt = jQuery('input.vehicle-price-field'),
        model_id = jQuery('.vehicle-model-wrap input.model-id');
    if (!customErrorField(model_id)) {
        customErrorField(model_id);
    }
    if (!customErrorField(inputElemt)) {
        customErrorField(inputElemt);
    } else {

        customErrorField(inputElemt);
        jQuery(this).find('.load-animate').css('display','inline-block');
        setTimeout(() => {
            jQuery(this).find('.load-animate').css('display','none');

            function calculateInsuranceFees(price, cost) {
                var result = (price * cost) / 100;
                return result;
            }

            var one_year = calculateInsuranceFees(price_value, one_year_cost);
            var two_year = calculateInsuranceFees(price_value, two_year_cost);
            var three_year = calculateInsuranceFees(price_value, three_year_cost);

            const formatter = new Intl.NumberFormat('it-IT', {
                style: 'currency',
                currency: 'VND'
            });


            jQuery('.price-calculated-one-year').text(formatter.format(one_year));
            jQuery('.price-calculated-two-year').text(formatter.format(two_year));
            jQuery('.price-calculated-three-year').text(formatter.format(three_year));

            jQuery('.done-calculate-insurance').addClass('show-popup');
            jQuery('.calculate-view-fields').css('display', 'none');
        }, 1000)
    }
})

// Recalculate Insurance

jQuery(document).on('click', '.recalculate-insurance', function (e) {
    e.preventDefault();
    jQuery('.vehicle-model-wrap input.vehicle-model-field').val('');
    jQuery('.vehicle-model-wrap input.model-id').val('');

    jQuery('.vehicle-grade-ctn').css('display', 'none');
    jQuery('.vehicle-grade-wrap input.vehicle-grade-field').val('');
    jQuery('.vehicle-grade-wrap input.vehicle-price-field').val('');
    jQuery('.list-vehicle-grade').html('');

    jQuery('.done-calculate-insurance').removeClass('show-popup');
    jQuery('.calculate-view-fields').css('display', 'block');
})


function hiddenCustomErrorField() {
    jQuery('.cus-error-field').css('display', 'none');
}
jQuery(document).on('click', '#btn-cost-estimate', function () {
    var phi_truoc_ba = Number(jQuery("#value_phi_truoc_ba").val()),
        phi_dang_ky_city = Number(jQuery("#value_phi_dang_ky_city").val()),
        phi_dang_ky_province = Number(jQuery("#value_phi_dang_ky_province").val()),
        phi_kiem_dinh = Number(jQuery("#value_phi_kiem_dinh").val()),
        phi_duong_bo = Number(jQuery("#value_phi_duong_bo").val()),
        phi_bao_hiem = Number(jQuery("#value_phi_bao_hiem_tnds").val());


    var cityID = jQuery('#province-show').children("option:selected").val();
    var provinceID = jQuery('#province-show').children("option:selected").val();
    var nameVehicle = jQuery('.vehicle-grade-field').val();
    var priceVehicle = Number(jQuery('.vehicle-price-field').val());

    var inputElemt = jQuery('input.vehicle-price-field'),
        model_id = jQuery('.vehicle-model-wrap input.model-id');

    if (model_id.val() && priceVehicle && cityID && provinceID) {
        jQuery(this).find('.load-animate').css('display','inline-block');

        setTimeout(() => {
            jQuery(this).find('.load-animate').css('display','none');

            const formatter = new Intl.NumberFormat('it-IT', {
                style: 'currency',
                currency: 'VND'
            });

            const formatterDecimal = new Intl.NumberFormat('it-IT', {
                style: 'decimal',
                minimumFractionDigits: 2,
            });

            function showResultCostEstimate(priceVehicle, phi_truoc_ba, phi_dang_ky_city_provine, phi_kiem_dinh, phi_duong_bo, phi_bao_hiem) {
                var total = priceVehicle + (priceVehicle * phi_truoc_ba / 100) + phi_dang_ky_city_provine + phi_kiem_dinh + phi_duong_bo + phi_bao_hiem;

                jQuery('.name-car-cost').text(nameVehicle);
                jQuery('.calculated-cost .gia-xe').text(formatter.format(priceVehicle));
                jQuery('.calculated-cost .phi-truoc-ba').text(formatterDecimal.format(phi_truoc_ba) + '%');
                jQuery('.calculated-cost .phi-dang-ky').text(formatter.format(phi_dang_ky_city_provine));
                jQuery('.calculated-cost .phi-kiem-dinh').text(formatter.format(phi_kiem_dinh));
                jQuery('.calculated-cost .phi-duong-bo').text(formatter.format(phi_duong_bo));
                jQuery('.calculated-cost .phi-bao-hiem').text(formatter.format(phi_bao_hiem));
                jQuery('#result-cost-estimates').text(formatter.format(total));
            }

            if (cityID == 202 || cityID == 201) {
                showResultCostEstimate(priceVehicle, phi_truoc_ba, phi_dang_ky_city, phi_kiem_dinh, phi_duong_bo, phi_bao_hiem);

                jQuery('.popup-cost').css('display', 'block');
                jQuery('.wrap-field-cost-estimates').css('display', 'none');
            } else {
                showResultCostEstimate(priceVehicle, phi_truoc_ba, phi_dang_ky_province, phi_kiem_dinh, phi_duong_bo, phi_bao_hiem);

                jQuery('.popup-cost').css('display', 'block');
                jQuery('.wrap-field-cost-estimates').css('display', 'none');
            }
        }, 1000);

    } else {
        customErrorField(model_id);
        customErrorField(inputElemt)
    }
});

jQuery(document).on('click', '.recalculate-cost', function (e) {
    e.preventDefault();

    jQuery('.vehicle-model-field').val('');
    jQuery('.vehicle-grade-ctn').css('display', 'none');
    jQuery('.district-wrap').css('display', 'none');
    jQuery('input.vehicle-price-field').val('');
    jQuery('.vehicle-model-wrap input.model-id').val('');
    jQuery('#province-show option[value=0]').attr('selected', 'selected');
    jQuery('.popup-cost').css('display', 'none');
    jQuery('.wrap-field-cost-estimates').css('display', 'block');
})
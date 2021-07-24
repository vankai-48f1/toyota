// FUNCTION CLICK MODEL VEHICLE

jQuery(document).on("click", ".list-vehicle-model > li", function (e) {
    jQuery(".list-vehicle-model > li").removeClass("active");
    jQuery(this).addClass("active");
    // jQuery(".list-vehicle-grade").css('display', 'none');

    var nameCarModel = jQuery(this).attr("data-name");
    var modelId = Number(jQuery(this).attr("data-model-id"));
    var modelIdPreview = jQuery(".preview-vihicle-grade");

    var htmlListVehicle = "";

    modelIdPreview.each((index, elemt) => {
        var elemtId = Number(jQuery(elemt).attr("data-model-id"));
        var name_vehicle = jQuery(elemt).attr("data-name");
        var price_vehicle = jQuery(elemt).attr("data-price");
        var insurance = jQuery(elemt).attr("data-insurance");

        if (modelId === elemtId) {
            htmlListVehicle += `
                    <li class="name-vihicle-grade" data-name="${name_vehicle}" data-price="${price_vehicle}" data-insurance="${insurance}">
                        <div class="vehicle-grade-name">
                            <p>${name_vehicle}</p>
                        </div>
                    </li>`;
        }
    });
    jQuery(".vehicle-grade-ctn").css("display", "block");
    jQuery(".list-vehicle-grade").html(htmlListVehicle);

    jQuery(this)
        .closest(".vehicle-model-wrap")
        .find('input[type="text"].vehicle-model-field')
        .val(nameCarModel);
    jQuery(".list-vehicle-model").removeClass("show-view");
    jQuery("input.model-id").val(modelId);
    jQuery('input[type="text"].vehicle-model-field').trigger("change");
    jQuery("input.vehicle-grade-field").val("");

    var inputElemt = jQuery("input.vehicle-price-field"),
        model_id = jQuery(".vehicle-model-wrap input.model-id");

    customErrorField(inputElemt);
    hiddenCustomErrorField();
});

// FUNCTION CLICK VEHICLE GRADE

jQuery(document).on("click", ".list-vehicle-grade > li", function (e) {
    jQuery(".list-vehicle-grade > li").removeClass("active");
    jQuery(this).addClass("active");

    var nameVehicle = jQuery(this).attr("data-name");
    var gradePrice = jQuery(this).attr("data-price");
    var insurance = jQuery(this).attr("data-insurance");

    jQuery(this)
        .closest(".vehicle-grade-wrap")
        .find('input[type="text"].vehicle-grade-field')
        .val(nameVehicle);

    jQuery(".list-vehicle-grade").removeClass("show-view");
    jQuery("input.vehicle-price-field").val(gradePrice);
    jQuery("#value_phi_bao_hiem_tnds").val(insurance);

    var inputElemt = jQuery("input.vehicle-price-field"),
        model_id = jQuery(".vehicle-model-wrap input.model-id");
    if (!customErrorField(inputElemt)) {
        customErrorField(inputElemt);
    }

});

jQuery(document).on("click", ".list-vehicle-grade > li", function (e) {
    var price_vehicle = Number(jQuery('.vehicle-grade-wrap .vehicle-price-field').val());

    const formatter = new Intl.NumberFormat('it-IT', {
        style: 'decimal',
        maximumFractionDigits: 0
    });

    jQuery('.row-price-vehicle').css('display', 'block');
    jQuery('.row-price-vehicle input[name="price_vehicle"]').val(formatter.format(price_vehicle));

})



jQuery(document).on("click", function (e) {
    e.stopPropagation();

    var wrapVehicleModel = jQuery(".vehicle-model-wrap");
    var listVehicleModel = jQuery(".list-vehicle-model");
    var wrapVehicleGrade = jQuery(".vehicle-grade-wrap");
    var listVehicleGrade = jQuery(".list-vehicle-grade");

    function removeShowViewDropCar(focusElemt, elementRemove) {
        if (!focusElemt.is(e.target) && focusElemt.has(e.target).length === 0) {
            elementRemove.removeClass("show-view");
        }
    }
    removeShowViewDropCar(wrapVehicleModel, listVehicleModel);
    removeShowViewDropCar(wrapVehicleGrade, listVehicleGrade);
});


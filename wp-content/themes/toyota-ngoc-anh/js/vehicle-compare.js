// open list vehicle
jQuery(document).on('click', '.vehicle-compare-blank', function (e) {
    e.preventDefault();

    var id_blank =  jQuery(this).closest('.box-vehicle').attr('id');

    var containerVehicle = jQuery('.vehicle-compare-list');

    jQuery('.page-vehicle-compare').css('display', 'none');

    containerVehicle.css('display', 'block');
    containerVehicle.attr('data-id-box', id_blank);
});


// Choose vehicle

jQuery(document).on('click', '.choose-this', function () {
    jQuery('.vehicle-compare-list').css('display', 'none');
    jQuery('.page-vehicle-compare').css('display', 'block');

    // Disable button
    jQuery(this).attr('disabled', true);

    var data_vehicle = jQuery(this).parent().find('.data-vehicle-item'),
        data_box     = jQuery(this).closest('.vehicle-compare-list').attr('data-id-box');

    var id_vehicle      = data_vehicle.attr('data-id-vehicle'),
        name_vehicle    = data_vehicle.attr('data-name-vehicle'),
        img_vehicle     = data_vehicle.attr('data-src-img');

    var box_vehicle     = jQuery('.box-vehicle');

    // Check choose blank this -> add vehicle to this
    box_vehicle.each( (index, element) => {
        var box_id = jQuery(element).attr('id');

        if ( box_id === data_box ) {
            jQuery(element).find('.vehicle-compare-blank-show').html(`<img src="${img_vehicle}" alt="" />`);
            jQuery(element).find('.vehicle-compare-blank-show').css('display', 'block');
            jQuery(element).find('.vehicle-compare-blank').css('display', 'none');

            jQuery(element).find('.vehicle-compare-name-show').html(name_vehicle);
            jQuery(element).find('.vehicle-compare-name').css('display', 'none');

            jQuery(element).find('input[name="id-vehicle"] ').val(id_vehicle);
            
            jQuery(element).find('.cancel-vehicle').css('display', 'inline-block');
        }
    })

    // Check complated value 
    var vehicle_blank_1 = jQuery('#vehicle-blank-1 input[name="id-vehicle"]').val();
    var vehicle_blank_2 = jQuery('#vehicle-blank-2 input[name="id-vehicle"]').val();

    if (vehicle_blank_1 && vehicle_blank_2 ) {
        jQuery('#button-vehicle-compare').removeAttr("disabled");
    }

    var heightHeader = jQuery('#header').height();
    window.scrollTo(heightHeader, 0)
})


// Cancel vehicle

jQuery(document).on('click','.cancel-vehicle', function () {
    var parent = jQuery(this).closest('.box-vehicle');
    parent.find('.vehicle-compare-blank-show').html("");
    parent.find('.vehicle-compare-blank-show').css('display', 'none');
    parent.find('.vehicle-compare-blank').css('display', 'block');

    parent.find('.vehicle-compare-name-show').html("");
    parent.find('.vehicle-compare-name').css('display', 'block');

    var id_vehicle = parent.find('input[name="id-vehicle"] ').val();
    
    var data_vehicle = jQuery('.vehicle-compare-list').find('.data-vehicle-item');
    data_vehicle.each( (index, elm) => {
        var data_id = jQuery(elm).attr('data-id-vehicle');

        if(id_vehicle === data_id ) {   
            jQuery(elm).parent().find('button.choose-this').attr('disabled', false);
        }
    });

    parent.find('input[name="id-vehicle"] ').val("");
    
    jQuery(this).css('display', 'none');
})
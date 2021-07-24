import handleView from "./modules/handleView.js"

jQuery(document).on("click", '#button-vehicle-compare', function () {
    var vehicle_1 = jQuery('#vehicle-blank-1 input[name="id-vehicle"]').val();
    var vehicle_2 = jQuery('#vehicle-blank-2 input[name="id-vehicle"]').val();

    var adminJax = jQuery("#url-admin-ajax").val();

    if (vehicle_1 && vehicle_2) {
        jQuery(this).find('.load-animate').css('display', 'inline-block');


        jQuery.ajax({
            url: adminJax,
            type: "post",
            dataType: "json",
            data: {
                action: "vehicle_compare",
                vehicle_1: vehicle_1,
                vehicle_2: vehicle_2
            },
            beforeSend: function () {
                // jQuery('.load-animate').css('display', 'none');

            },
            success: function (result) {
                jQuery('.result-compare-content table thead tr').html(handleView(result).thead);
                jQuery('.result-compare-content table tbody[data-vehicle="engine-and-chassis"]').html(handleView(result).dongCoVaKhungXe);
                jQuery('.result-compare-content table tbody[data-vehicle="exterior"]').html(handleView(result).ngoaiThat);
                jQuery('.result-compare-content table tbody[data-vehicle="furniture"]').html(handleView(result).noiThat);
                jQuery('.result-compare-content table tbody[data-vehicle="convenient"]').html(handleView(result).tienNghi);
                jQuery('.result-compare-content table tbody[data-vehicle="active-safety"]').html(handleView(result).anToanChuDong);
                jQuery('.result-compare-content table tbody[data-vehicle="passive-safety"]').html(handleView(result).anToanBiDong);
                jQuery('.result-compare-content table tbody[data-vehicle="security"]').html(handleView(result).anNinh);
            },
            complete: function () {
                jQuery('.load-animate').css('display', 'none');
                jQuery('.page-vehicle-compare').css('display', 'none');
                jQuery('.result-compare ').css('display', 'block');
            },
            error: function (err) {
                console.warn('Lỗi ' + err);
            },
        });
    }

}
);

// Navigation

var target = jQuery('.result-compare-content table tbody[data-vehicle]');
var nav = jQuery('.nav-result-compare li');

nav.each((index, elemt) => {
    if (jQuery(elemt).hasClass('active')) {
        var nav_item = jQuery(elemt).attr('data-target');
        jQuery('.result-compare-content table tbody[data-vehicle="' + nav_item + '"]').css('display', 'revert');
    }
})

jQuery(document).on('click', '.nav-result-compare li', function () {
    nav.removeClass('active');

    target.css('display', 'none');

    jQuery(this).addClass('active');

    var data_target = jQuery(this).attr('data-target');
    jQuery('.result-compare-content table tbody[data-vehicle="' + data_target + '"]').css('display', 'revert');

})



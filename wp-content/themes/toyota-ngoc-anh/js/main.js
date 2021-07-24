(function ($) {
    $(document).ready(function () {
        // Custom dropdown brach field

        // input
        var inputBranch = $('.form-register form input[name="branch"]'),
            parentInputBranch = inputBranch.parent();

        // list branch
        var listBranch = $(".na-branch-list");

        // append list branch
        parentInputBranch.append(listBranch);

        inputBranch.on("focus", function (e) {
            e.stopPropagation();
            $(this).parent().find(".na-branch-list").addClass("drop");
        });

        $(".na-branch-list li").on("click", function (e) {
            e.stopPropagation();

            var name_branch = $(this).find(".name_branch").text();
            var location_branch = $(this).find(".location_branch").text();

            $(this)
                .closest(".cus-drop-field")
                .find('input[name="branch"]')
                .val(name_branch);

            $(this)
                .closest(".cus-drop-field")
                .find('input[name="branch-location-field"]')
                .val(location_branch);

            $(".form-register form .na-branch-list").removeClass("drop");
        });

        $(document).on("click", function (e) {
            e.stopPropagation();

            var container = $(".cus-drop-field");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $(".form-register form .na-branch-list").removeClass("drop");
            }
        });

        // margin-top Liên hệ
        // var heightHeader = $("#header").height();
        // function marginTop(heightHeader, elementMargin) {
        //     elementMargin.css("margin-top", heightHeader + "px");
        // }
        // marginTop(heightHeader, $(".head-contact-us"));
        // marginTop(heightHeader, $(".categories-news"));


        // show branch

        var coordinates = $(".map-branch-list > li.active .map-name-branch").attr("data-iframe-map");
        var iframeMap = $(".map-branch-view iframe");
        var iframeMapSrc =
            "https://maps.google.com/maps?q=" + coordinates + "&output=embed";
        iframeMap.attr("src", iframeMapSrc);

        $(".map-branch-list > li .map-name-branch").on("click", function () {
            $(".map-branch-list > li").removeClass("active");
            $(".map-branch-list > li .map-address-branch").removeClass("show");
            $(this).parent().addClass("active");
            $(this).parent().find(".map-address-branch").addClass("show");

            coordinates = $(this).attr("data-iframe-map");
            iframeMapSrc =
                "https://maps.google.com/maps?q=" + coordinates + "&output=embed";
            iframeMap.attr("src", iframeMapSrc);
        });

        // CUSTOM ICON CHECKED

        function addIconChecked(inputElement) {
            inputElement.parent().append('<span class="cus-icon-check"></span>');
        }
        function removeCheckedDefaulInput(inputElement) {
            inputElement.prop('checked', false);
        }
        addIconChecked($('.wpcf7-form input[type="radio"]'));
        removeCheckedDefaulInput($('.wpcf7-form input[type="radio"]'));
        // addIconChecked($('.wpcf7-form input[type="checkbox"]'));

        // $(document).on("click", ".cus-icon-check", function () {
        //   $(this).addClass("checked");
        // });
    });
})(jQuery);

function customErrorField(inputElemt) {
    var inputValue = inputElemt.val();
    var num_price_value = Number(inputValue);

    if (num_price_value == 0 || !num_price_value) {
        inputElemt.closest('.car-form-row').find('.cus-error-field').html('<span class="cl-red">Vui lòng nhập dữ liệu</span>');
        inputElemt.closest('.car-form-row').find('.cus-error-field').css('display', 'block');

        return false;
    } else {
        inputElemt.closest('.car-form-row').find('.cus-error-field').css('display', 'none');
        return true;
    }
}
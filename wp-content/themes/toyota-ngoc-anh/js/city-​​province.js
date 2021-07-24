(function ($) {
    $(document).ready(function () { }); // END DOCUMENT READY
})(jQuery);

jQuery(window).on("load", function () {
    jQuery.ajax({
        url: "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province",
        headers: {
            "token": "5cd14bf5-df31-11eb-9388-d6e0030cbbb7",
            "Content-Type": "application/json",
        },
        method: "GET",
        dataType: "json",
        success: function (reponse) {
            var provinceData = reponse.data;
            var select = document.getElementById("province-show");
            var option = '<option value="0"></option>';

            provinceData.forEach((element) => {
                option += `<option value="${element.ProvinceID}" data-province="${element.ProvinceID}">${element.ProvinceName}</option>`;
            });

            if (select) select.innerHTML = option;
        },
    });
});

function provinceChange() {
    // DISPLAY DISTRICT BLOCK
    document.querySelector(".district-wrap").style.display = "flex";

    var province = document.getElementById("province-show");
    var district = document.getElementById("district-show");

    var idProvince = province.options[province.selectedIndex].getAttribute("data-province");
    var dataProvince = province.options[province.selectedIndex].value;

    if (dataProvince == 0 ) {
        document.querySelector(".district-wrap").style.display = "none";
    }

    jQuery.ajax({
        url: "https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district",
        headers: {
            token: "5cd14bf5-df31-11eb-9388-d6e0030cbbb7",
            "Content-Type": "application/json",
        },
        method: "GET",
        dataType: "json",
        success: function (reponse) {
            var districtData = reponse.data;
            var select = document.getElementById('district-show');
            var option = '<option value="0"></option>';

            districtData.forEach(element => {
                if (idProvince == element.ProvinceID) {
                    option += `<option value=" ${element.DistrictID} " data-province="${element.ProvinceID}">${element.DistrictName}</option>`;

                }
            });

            if (select) select.innerHTML = option;
        },
    });
}

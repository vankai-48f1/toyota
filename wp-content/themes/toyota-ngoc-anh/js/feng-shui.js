
// max date today
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth() + 1;
var yyyy = today.getFullYear();
if (dd < 10) {
    dd = '0' + dd
}
if (mm < 10) {
    mm = '0' + mm
}

today = yyyy + '-' + mm + '-' + dd;
var input_date =  document.getElementById("feng-shui-date");
if(input_date) {

    input_date.setAttribute("max", today);
}



jQuery(document).on('change', '.feng-shui-content input[type="date"]', function () {
    var value_date = jQuery(this).val(),
        fullDate = new Date(value_date),
        date = fullDate.getDate(),
        month = fullDate.getMonth() + 1,
        year = fullDate.getFullYear();

    if (date < 10) {
        date = '0' + date;
    }
    if (month < 10) {
        month = '0' + month;
    }

    var formatDate = `${date}/${month}/${year}`;
    jQuery('.feng-shui-content input[name="date_formatted"]').val(formatDate);
})

jQuery(document).on("click", "#btn-feng-shui", function () {
    var date = jQuery('.feng-shui-content input[name="date_formatted"]').val();

    // jQuery.ajax({
    //     url: "https://api-dealer.carbro.com/api/dongsg/fengshui/colors?date=12/07/2003",
    //     // headers: {
    //     //     "x-auth-id": 9423675198186518,
    //     //     "Access-Control-Allow-Origin": "https://www.toyotasaigon.com"
    //     // },
    //     method: "GET",
    //     dataType: "json",
    //     crossDomain: true,
    //     // dataType: 'jsonp',
    //     // data: {
    //     //     date: date,
    //     // },
    //     beforeSend: function (request) {
    //         request.setRequestHeader("x-auth-id", 9423675198186518);
    //         request.setRequestHeader("Access-Control-Allow-Origin", "https://www.toyotasaigon.com");
    //         // request.setRequestHeader("X-Alt-Referer", "https://www.toyotasaigon.com");

    //         request.setRequestHeader("origin", "https://www.toyotasaigon.com");
    //         request.setRequestHeader("referer", "https://www.toyotasaigon.com");
    //     },
    //     success: function (reponse) {
    //         console.log(reponse);
    //     },
    //     error: function (error) {
    //         console.log(error);

    //         // jQuery('.feng-shui-error').append('Error: ' + errorMessage);
    //     }
    // });

    var feng_shi_url = "https://api-dealer.carbro.com/api/dongsg/fengshui/colors?date=12/07/2003";
    fetch(feng_shi_url, { 
        method: 'GET',
        mode: 'cors',
        // credentials: 'omit',
        // referrerPolicy: 'no-referrer',
        headers: {
            'Content-Type': 'application/json',
            'x-auth-id': 9423675198186518,
            'Access-Control-Allow-Origin': "https://toyotangocanhlamdong.vn",
            // 'Access-Control-Allow-Headers': "*",
        },     
     })
    .then( function (response) {

        return response.json();
    })
    .then( function (data) {
        console.log(data);
    })
    .catch( function (error) {
        alert(error)
    })
    

});





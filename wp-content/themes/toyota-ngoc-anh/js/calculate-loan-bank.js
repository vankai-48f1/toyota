( function () {
    const formatNum = new Intl.NumberFormat('it-IT', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumSignificantDigits: 20
    });
    jQuery('input.prepayment-amount').on('blur', function () { 
        var value  = jQuery(this).val();
        var currency = formatNum.format(value);
         jQuery(this).val(currency)
    });


})(jQuery)


jQuery(document).on('click', '#btn-loan-bank', function (e) {
    e.preventDefault();
    var prepayment = jQuery('.wrap-field-loan-bank .prepayment-amount').val();
    var conver_prepayment = prepayment.split('.').join("")

    var price_vehicle = Number(jQuery('.vehicle-grade-wrap .vehicle-price-field').val()),
        prepayment_amount = Number(conver_prepayment),
        interest_rate = Number(jQuery('.wrap-field-loan-bank .interest-rate').val()),
        loan_bank_term = Number(jQuery('#loan-bank-term').children("option:selected").val());

    const formatter = new Intl.NumberFormat('it-IT', {
        style: 'decimal',
        maximumFractionDigits: 0
    });

    function calculateLoanBank(price_vehicle, prepayment_amount, interest_rate, loan_bank_term) {
        var debt = price_vehicle - prepayment_amount;
        var amount_month = debt / loan_bank_term;
        var result_cal_loan_ban = '';

        for (let i = 1; i < debt; ++i) {
            var interest = (debt * interest_rate) / 100;
            var principal_and_interest = amount_month + interest;

            result_cal_loan_ban += `
                              <tr>
                                 <td>${i}</td>
                                 <td>${formatter.format(debt)}</td>
                                 <td>${formatter.format(interest)}</td>
                                 <td>${formatter.format(principal_and_interest)}</td>
                              </tr>
                             
                `;
            debt = debt - amount_month;

        }

        jQuery('.result-cal-loan-bank').css('display', 'block');
        jQuery('.result-cal-loan-bank tbody').html(result_cal_loan_ban);
        jQuery('.result-cal-loan-bank table caption strong').html(formatter.format(amount_month) + ' VND');
    }

    var inputElemt = jQuery('input.vehicle-price-field'),
    model_id = jQuery('.vehicle-model-wrap input.model-id');


    if (price_vehicle && prepayment_amount && interest_rate && loan_bank_term) {
        jQuery(this).find('.load-animate').css('display', 'inline-block');

        setTimeout(() => {
            jQuery(this).find('.load-animate').css('display', 'none');

            calculateLoanBank(price_vehicle, prepayment_amount, interest_rate, loan_bank_term);
        }, 1000);
    } else {
        customErrorField(model_id);
        customErrorField(inputElemt)
    }
})


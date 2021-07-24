(function ($) {
	$(document).ready(function () {

		// VIEW CHOOSE TEST DRIVER
		var dataCateCar = $('.pre-cate-car .data-cate-car');
		var sliderChooserCar = $('.slide-choose-car');

		function sliderChooseCarHtml(name, image, increment) {
			var htmlSlider = `
		<div class="choose-car-item">
			<div class="choose-car-thumbnail">
				<img src="${image}" alt="" />
			</div>
			<div class="choose-car-item-field">
				
				<label for="vehicle_${increment}">
					<span class="vehicle_name">${name}</span>
					<input type="checkbox" id="vehicle_${increment}" name="vehicle_${increment}" value="${name}">
					<span class="cus-icon-check"></span>
				</label>
			</div>
		</div>`;
			return htmlSlider;
		}


		var increment = 0;
		dataCateCar.each((index, element) => {
			++increment;

			var dataName = $(element).attr("data-name");
			var dataImage = $(element).attr("data-image");

			sliderChooserCar.append(sliderChooseCarHtml(dataName, dataImage, increment));
		});

		sliderChooserCar.slick({
			infinite: false,
			slidesToShow: 3,
			slidesToScroll: 3
		});

		// SET WIDTH THUMBNAI 

		$(document).on('click', '.slide-choose-car .choose-car-item-field > label', function () {
			var inputChecked = $('.slide-choose-car .choose-car-item-field label input:checked');
			var fieldName = $('.choose-car-test input[name="test-car-name"]');
			var groupValue = '';

			inputChecked.each((index, elm) => {
				var value = $(elm).val();
				var seperate = index > 0 ? ', ' : '';

				groupValue += seperate + value;

				fieldName.val(groupValue);
			})
		});

		// POPUP AGREE RULES
		var buttonAgreeRulesContent = 'Tôi đồng ý với <a href="#" data-toggle="modal" data-target="#myPopupAgreeRules">điều kiện và điều khoản</a> của chương trình.' 
		jQuery('.agree-rules-field .wpcf7-list-item label .wpcf7-list-item-label').html(buttonAgreeRulesContent);
	});
})(jQuery);

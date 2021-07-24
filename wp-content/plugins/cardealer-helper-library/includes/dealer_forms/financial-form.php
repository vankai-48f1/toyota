<?php
/**
 * This function use to store new inquiries of test drive.
 *
 * @package car-dealer-helper
 */

add_action( 'wp_ajax_financial_form_action', 'cdhl_financial_form_action' );
add_action( 'wp_ajax_nopriv_financial_form_action', 'cdhl_financial_form_action' );
if ( ! function_exists( 'cdhl_financial_form_action' ) ) {
	/**
	 * Financial Form
	 */
	function cdhl_financial_form_action() {
		global $car_dealer_options;

		$response_array = array();

		if ( isset( $_POST['action'] ) && 'financial_form_action' === $_POST['action'] ) {
			$captcha                             = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
			$recaptcha_response                  = cardealer_validate_google_captch( $captcha );
			$first_name                          = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
			$middle_initial                      = isset( $_POST['middle_initial'] ) ? sanitize_text_field( wp_unslash( $_POST['middle_initial'] ) ) : '';
			$last_name                           = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
			$street_address                      = isset( $_POST['street_address'] ) ? sanitize_text_field( wp_unslash( $_POST['street_address'] ) ) : '';
			$city                                = isset( $_POST['city'] ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
			$zip                                 = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
			$preferred_email_address             = isset( $_POST['preferred_email_address'] ) ? sanitize_text_field( wp_unslash( $_POST['preferred_email_address'] ) ) : '';
			$daytime_phone_number                = isset( $_POST['daytime_phone_number'] ) ? sanitize_text_field( wp_unslash( $_POST['daytime_phone_number'] ) ) : '';
			$mobile_phone_number                 = isset( $_POST['mobile_phone_number'] ) ? sanitize_text_field( wp_unslash( $_POST['mobile_phone_number'] ) ) : '';
			$date_of_birth_month                 = isset( $_POST['date_of_birth_month'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth_month'] ) ) : '';
			$date_of_birth_day                   = isset( $_POST['date_of_birth_day'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth_day'] ) ) : '';
			$date_of_birth_year                  = isset( $_POST['date_of_birth_year'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth_year'] ) ) : '';
			$social_security_number              = isset( $_POST['social_security_number'] ) ? sanitize_text_field( wp_unslash( $_POST['social_security_number'] ) ) : '';
			$employer_name                       = isset( $_POST['employer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['employer_name'] ) ) : '';
			$employer_phone                      = isset( $_POST['employer_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['employer_phone'] ) ) : '';
			$job_title                           = isset( $_POST['job_title'] ) ? sanitize_text_field( wp_unslash( $_POST['job_title'] ) ) : '';
			$annual_income                       = isset( $_POST['annual_income'] ) ? sanitize_text_field( wp_unslash( $_POST['annual_income'] ) ) : '';
			$living_arrangements                 = isset( $_POST['living_arrangements'] ) ? sanitize_text_field( wp_unslash( $_POST['living_arrangements'] ) ) : '';
			$length_of_time_at_current_add_year  = isset( $_POST['length_of_time_at_current_add_year'] ) ? sanitize_text_field( wp_unslash( $_POST['length_of_time_at_current_add_year'] ) ) : '';
			$length_of_time_at_current_add_month = isset( $_POST['length_of_time_at_current_add_month'] ) ? sanitize_text_field( wp_unslash( $_POST['length_of_time_at_current_add_month'] ) ) : '';
			$length_of_employment_year           = isset( $_POST['length_of_employment_year'] ) ? sanitize_text_field( wp_unslash( $_POST['length_of_employment_year'] ) ) : '';
			$length_of_employment_month          = isset( $_POST['length_of_employment_month'] ) ? sanitize_text_field( wp_unslash( $_POST['length_of_employment_month'] ) ) : '';
			$other_income_ammount_monthly        = isset( $_POST['other_income_ammount_monthly'] ) ? sanitize_text_field( wp_unslash( $_POST['other_income_ammount_monthly'] ) ) : '';
			$other_income_source                 = isset( $_POST['other_income_source'] ) ? sanitize_text_field( wp_unslash( $_POST['other_income_source'] ) ) : '';
			$additional_information              = isset( $_POST['additional_information'] ) ? sanitize_text_field( wp_unslash( $_POST['additional_information'] ) ) : '';
			$joint_application                   = ( isset( $_POST['joint_application'] ) ) ? sanitize_text_field( wp_unslash( $_POST['joint_application'] ) ) : '';
			$monthly_rent                        = isset( $_POST['monthly_rent'] ) ? sanitize_text_field( wp_unslash( $_POST['monthly_rent'] ) ) : '';
			$car_id                              = isset( $_POST['car_id'] ) ? sanitize_text_field( wp_unslash( $_POST['car_id'] ) ) : '';
			$author_id                           = get_post_field( 'post_author', $car_id );
			$user                                = get_userdata( $author_id );
			$user_roles                          = $user->roles;
			$from_name                           = $user->display_name;

			if ( in_array( 'administrator', $user_roles, true ) ) {
				$from_name = $car_dealer_options['financial_form_from_name'];
			}

			if ( isset( $_POST['joint_application'] ) && 'on' === $_POST['joint_application'] ) {
				$joint_first_name                         = isset( $_POST['first_name_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name_joint'] ) ) : '';
				$joint_middle_initial                     = isset( $_POST['middle_initial_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['middle_initial_joint'] ) ) : '';
				$joint_last_name                          = isset( $_POST['last_name_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name_joint'] ) ) : '';
				$joint_relationship_to_applicant          = isset( $_POST['relationship_to_applicant'] ) ? sanitize_text_field( wp_unslash( $_POST['relationship_to_applicant'] ) ) : '';
				$joint_street_address                     = isset( $_POST['street_address_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['street_address_joint'] ) ) : '';
				$joint_city                               = isset( $_POST['city_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['city_joint'] ) ) : '';
				$joint_state                              = isset( $_POST['state_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['state_joint'] ) ) : '';
				$joint_zip                                = isset( $_POST['zip_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['zip_joint'] ) ) : '';
				$joint_preferred_email_address            = isset( $_POST['preferred_email_address_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['preferred_email_address_joint'] ) ) : '';
				$joint_daytime_phone_number               = isset( $_POST['daytime_phone_number_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['daytime_phone_number_joint'] ) ) : '';
				$joint_mobile_phone_number                = isset( $_POST['mobile_phone_number_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['mobile_phone_number_joint'] ) ) : '';
				$joint_date_of_birth_month                = isset( $_POST['date_of_birth_month_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth_month_joint'] ) ) : '';
				$joint_date_of_birth_day                  = isset( $_POST['date_of_birth_day_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth_day_joint'] ) ) : '';
				$joint_date_of_birth_year                 = isset( $_POST['date_of_birth_year_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['date_of_birth_year_joint'] ) ) : '';
				$joint_social_security_number             = isset( $_POST['social_security_number_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['social_security_number_joint'] ) ) : '';
				$joint_employer_name                      = isset( $_POST['employer_name_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['employer_name_joint'] ) ) : '';
				$joint_employer_phone                     = isset( $_POST['employer_phone_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['employer_phone_joint'] ) ) : '';
				$joint_job_title                          = isset( $_POST['job_title_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['job_title_joint'] ) ) : '';
				$joint_length_of_employment_year          = isset( $_POST['length_of_employment_year_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['length_of_employment_year_joint'] ) ) : '';
				$joint_length_of_employment_month         = isset( $_POST['length_of_employment_month_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['length_of_employment_month_joint'] ) ) : '';
				$joint_annual_income                      = isset( $_POST['annual_income_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['annual_income_joint'] ) ) : '';
				$joint_living_arrangements                = isset( $_POST['living_arrangements_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['living_arrangements_joint'] ) ) : '';
				$joint_monthly_rent                       = isset( $_POST['monthly_rent_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['monthly_rent_joint'] ) ) : '';
				$joint_length_of_time_at_current_add_year = isset( $_POST['length_of_time_at_current_add_year_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['length_of_time_at_current_add_year_joint'] ) ) : '';
				$joint_other_income_ammount_monthly       = isset( $_POST['other_income_ammount_monthly_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['other_income_ammount_monthly_joint'] ) ) : '';
				$joint_other_income_source                = isset( $_POST['other_income_source_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['other_income_source_joint'] ) ) : '';
				$joint_additional_information             = isset( $_POST['additional_information_joint'] ) ? sanitize_text_field( wp_unslash( $_POST['additional_information_joint'] ) ) : '';
			}
			if ( ! isset( $_POST['financial_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['financial_nonce'] ), 'financial_form' ) ) {
				$errors[] = esc_html__( 'Sorry, Something went wrong. Refresh Page and try again.', 'cardealer-helper' );
			} elseif ( false === (bool) $recaptcha_response['success'] ) {
				$errors[] = esc_html__( 'Please check the the captcha form', 'cardealer-helper' );
			} else {
				$mail_set     = 0;
				$request_date = ( isset( $recaptcha_response['challenge_ts'] ) && ! empty( $recaptcha_response['challenge_ts'] ) ) ? $recaptcha_response['challenge_ts'] : '';
				$post_id      = wp_insert_post(
					array(
						'post_status'  => 'publish',
						'post_type'    => 'financial_inquiry',
						'post_title'   => $first_name . ' ' . $last_name,
						'post_content' => '',
					)
				);

				if ( $post_id ) {
					if ( isset( $car_id ) && ! empty( $car_id ) ) {
						$caryear           = wp_get_post_terms( $car_id, 'car_year' );
						$car_year          = ( ! is_wp_error( $caryear ) && ! empty( $caryear ) ) ? $caryear[0]->name : '';
						$carmake           = wp_get_post_terms( $car_id, 'car_make' );
						$car_make          = ( ! is_wp_error( $carmake ) && ! empty( $carmake ) ) ? $carmake[0]->name : '';
						$carmodel          = wp_get_post_terms( $car_id, 'car_model' );
						$car_model         = ( ! is_wp_error( $carmodel ) && ! empty( $carmodel ) ) ? $carmodel[0]->name : '';
						$cartrim           = wp_get_post_terms( $car_id, 'car_trim' );
						$car_trim          = ( ! is_wp_error( $cartrim ) && ! empty( $cartrim ) ) ? $cartrim[0]->name : '';
						$carvin            = wp_get_post_terms( $car_id, 'car_vin_number' );
						$car_vin           = ( ! is_wp_error( $carvin ) && ! empty( $carvin ) ) ? $carvin[0]->name : '';
						$carstock          = wp_get_post_terms( $car_id, 'car_stock_number' );
						$car_stock         = ( ! is_wp_error( $carstock ) && ! empty( $carstock ) ) ? $carstock[0]->name : '';
						$regularprice      = get_post_meta( $car_id, 'regular_price', true );
						$car_regular_price = ( ! empty( $regularprice ) ) ? $regularprice : '';
						$saleprice         = get_post_meta( $car_id, 'sale_price', true );
						$car_sale_price    = ( ! empty( $saleprice ) ) ? $saleprice : '';

						update_field( 'car_id', $car_id, $post_id );
						update_field( 'car_year_inq', $car_year, $post_id );
						update_field( 'car_make_inq', $car_make, $post_id );
						update_field( 'car_model_inq', $car_model, $post_id );
						update_field( 'car_trim_inq', $car_trim, $post_id );
						update_field( 'vin_number', $car_vin, $post_id );
						update_field( 'stock_number', $car_stock, $post_id );
						update_field( 'regular_price', $car_regular_price, $post_id );
						update_field( 'sale_price', $car_sale_price, $post_id );
					}
					update_field( 'first_name', $first_name, $post_id );
					update_field( 'middle_initial', $middle_initial, $post_id );
					update_field( 'last_name', $last_name, $post_id );
					update_field( 'street_address', $street_address, $post_id );
					update_field( 'city', $city, $post_id );
					update_field( 'state', sanitize_text_field( wp_unslash( $_POST['state'] ) ), $post_id );
					update_field( 'zip', $zip, $post_id );
					update_field( 'preferred_email_address', $preferred_email_address, $post_id );
					update_field( 'daytime_phone_number', $daytime_phone_number, $post_id );
					update_field( 'mobile_phone_number', $mobile_phone_number, $post_id );
					update_field( 'date_of_birth', $date_of_birth_month . '-' . $date_of_birth_day . '-' . $date_of_birth_year, $post_id );
					update_field( 'living_arrangements', $living_arrangements, $post_id );
					update_field( 'ssn', $social_security_number, $post_id );
					update_field( 'employer_name', $employer_name, $post_id );
					update_field( 'monthly_rent_payment', $monthly_rent, $post_id );
					update_field( 'employer_phone', $employer_phone, $post_id );
					update_field( 'job_title', $job_title, $post_id );
					update_field( 'len_time_at_curr_addr', $length_of_time_at_current_add_year . ' ' . $length_of_time_at_current_add_month, $post_id );
					update_field( 'len_of_employment', $length_of_employment_year . ' ' . $length_of_employment_month, $post_id );
					update_field( 'annual_income', $annual_income, $post_id );
					update_field( 'other_income_amt', $other_income_ammount_monthly, $post_id );
					update_field( 'other_income_source', $other_income_source, $post_id );
					update_field( 'additional_info', $additional_information, $post_id );
					update_field( 'joint_application', $joint_application, $post_id );

					if ( isset( $_POST['joint_application'] ) && 'on' === $_POST['joint_application'] ) {
						update_field( 'joint_first_name', $joint_first_name, $post_id );
						update_field( 'joint_middle_name', $joint_middle_initial, $post_id );
						update_field( 'joint_last_name', $joint_last_name, $post_id );
						update_field( 'joint_relationship_to_applicant', $joint_relationship_to_applicant, $post_id );
						update_field( 'joint_street_address', $joint_street_address, $post_id );
						update_field( 'joint_city', $joint_city, $post_id );
						update_field( 'joint_state', $joint_state, $post_id );
						update_field( 'joint_zip', $joint_zip, $post_id );
						update_field( 'joint_preferred_email_address', $joint_preferred_email_address, $post_id );
						update_field( 'joint_day_time_phone_no', $joint_daytime_phone_number, $post_id );
						update_field( 'joint_mobile_phone_number', $joint_mobile_phone_number, $post_id );
						update_field( 'joint_date_of_birth', $joint_date_of_birth_month . '-' . $joint_date_of_birth_day . '-' . $joint_date_of_birth_year, $post_id );
						update_field( 'joint_living_arrangements', $joint_living_arrangements, $post_id );
						update_field( 'joint_ssn', $joint_social_security_number, $post_id );
						update_field( 'joint_employer_name', $joint_employer_name, $post_id );
						update_field( 'joint_monthly_rent_payment', $joint_monthly_rent, $post_id );
						update_field( 'joint_employer_phone', $joint_employer_phone, $post_id );
						update_field( 'joint_job_title', $joint_job_title, $post_id );
						update_field( 'joint_len_time_at_curr_addr', $joint_length_of_time_at_current_add_year . ' ' . sanitize_text_field( wp_unslash( $_POST['length_of_time_at_current_add_month_joint'] ) ), $post_id );
						update_field( 'joint_len_of_employment', $joint_length_of_employment_year . ' ' . $joint_length_of_employment_month, $post_id );
						update_field( 'joint_annual_income', $joint_annual_income, $post_id );
						update_field( 'joint_other_income_amt', $joint_other_income_ammount_monthly, $post_id );
						update_field( 'joint_other_income', $joint_other_income_source, $post_id );
						update_field( 'joint_additional_info', $joint_additional_information, $post_id );
					}

					$from_name     = empty( $car_dealer_options['financial_form_from_name'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $car_dealer_options['financial_form_from_name'];
					$from_mail     = empty( $car_dealer_options['financial_form_mail_id_from'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : $car_dealer_options['financial_form_mail_id_from'];
					$reply_to_mail = empty( $_POST['preferred_email_address'] ) ? 'no-reply@' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : sanitize_text_field( wp_unslash( $_POST['preferred_email_address'] ) );
					$reply_to_name = empty( $first_name ) ? 'User' : $first_name;
					$subject       = empty( $car_dealer_options['financial_form_subject'] ) ? esc_html__( 'Test Drive Inquiry Mail', 'cardealer-helper' ) : $car_dealer_options['financial_form_subject'];

					$headers    = 'From: ' . strip_tags( $from_name ) . ' <' . $from_mail . ">\r\n";
					$headers   .= 'Reply-To: ' . strip_tags( $reply_to_name ) . ' <' . $reply_to_mail . ">\r\n";
					$mail_error = 0;

					// PREPARE PRODUCT DETAIL FOR MAIL.
					$adf_data   = '';
					$plain_mail = '';
					$product    = '';
					if ( isset( $car_id ) && ! empty( $car_id ) ) {
						$plain_mail = cdhl_get_text_mail_body( $car_id );
						$product    = cdhl_get_html_mail_body( $car_id );

						// SET MAIL BODY FOR ADF MAIL.
						$adf_data .= '<?xml version="1.0"?>' . PHP_EOL;
						$adf_data .= '<?ADF VERSION="1.0"?>' . PHP_EOL;
						$adf_data .= '<adf>' . PHP_EOL;
						$adf_data .= '<prospect>' . PHP_EOL;
						$adf_data .= '<requestdate>' . $request_date . '</requestdate>' . PHP_EOL;
						$adf_data .= '<vehicle>' . PHP_EOL;
						$adf_data .= '<year>' . $car_year . '</year>' . PHP_EOL;
						$adf_data .= '<make>' . $car_make . '</make>' . PHP_EOL;
						$adf_data .= '<model>' . $car_model . '</model>' . PHP_EOL;
						$adf_data .= '<vin>' . $car_vin . '</vin>' . PHP_EOL;
						$adf_data .= '<stock>' . $car_stock . '</stock>' . PHP_EOL;
						$adf_data .= '<trim>' . $car_trim . '</trim>' . PHP_EOL;
						$adf_data .= '</vehicle>' . PHP_EOL;
						$adf_data .= '<customer>' . PHP_EOL;
						$adf_data .= '<contact>' . PHP_EOL;
						$adf_data .= '<name part="full">' . $first_name . ' ' . $middle_initial . ' ' . $last_name . '</name>' . PHP_EOL;
						$adf_data .= '<email>' . sanitize_text_field( wp_unslash( $_POST['preferred_email_address'] ) ) . '</email>' . PHP_EOL;
						$adf_data .= '<phone type="voice" time="day">' . $mobile_phone_number . '</phone>' . PHP_EOL;
						$adf_data .= '<address>' . PHP_EOL;
						$adf_data .= '<street line="1">' . $street_address . '</street>' . PHP_EOL;
						$adf_data .= '<city>' . $city . '</city>' . PHP_EOL;
						$adf_data .= '<postalcode>' . $zip . '</postalcode>' . PHP_EOL;
						$adf_data .= '</address>' . PHP_EOL;
						$adf_data .= '</contact>' . PHP_EOL;
						$adf_data .= '<comments>' . PHP_EOL;

						// ADD FORM DATA INTO COMMENT.
						$customer_detail  = esc_html__( 'Financial Inquiry Information : ', 'cardealer-helper' ) . PHP_EOL . PHP_EOL;
						$customer_detail .= esc_html__( 'Financial Information :', 'cardealer-helper' ) . PHP_EOL . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer-helper' ) ) . ' :' . $first_name . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_middle_initial', esc_html__( 'Middle Initial', 'cardealer-helper' ) ) . ' :' . $middle_initial . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer-helper' ) ) . ' :' . $last_name . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_street_address', esc_html__( 'Street Address', 'cardealer-helper' ) ) . ' :' . $street_address . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_city', esc_html__( 'City', 'cardealer-helper' ) ) . ' :' . $city . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_state', esc_html__( 'State', 'cardealer-helper' ) ) . ' :' . sanitize_text_field( wp_unslash( $_POST['state'] ) ) . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_zip', esc_html__( 'Zip', 'cardealer-helper' ) ) . ' :' . $zip . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_preferred_email_address', esc_html__( 'Preferred Email Address', 'cardealer-helper' ) ) . ' :' . $preferred_email_address . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_daytime_phone_number', esc_html__( 'Daytime Phone Number', 'cardealer-helper' ) ) . ' :' . $daytime_phone_number . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer-helper' ) ) . ' :' . $mobile_phone_number . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_date_of_birth', esc_html__( 'Date of Birth', 'cardealer-helper' ) ) . ' :' . $date_of_birth_month . '-' . $date_of_birth_day . '-' . $date_of_birth_year . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_ssn', esc_html__( 'Social Security Number (SSN)', 'cardealer-helper' ) ) . ' :' . $social_security_number . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_employer_name', esc_html__( 'Employer Name', 'cardealer-helper' ) ) . ' :' . $employer_name . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_employer_phone', esc_html__( 'Employer Phone', 'cardealer-helper' ) ) . ' :' . $employer_phone . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_job_title', esc_html__( 'Job Title', 'cardealer-helper' ) ) . ' :' . $job_title . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_living_arrangements', esc_html__( 'Living Arrangements', 'cardealer-helper' ) ) . ' :' . $living_arrangements . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_monthly_rent_mortgage_payment', esc_html__( 'Monthly Rent/Mortgage Payment', 'cardealer' ) ) . ' :' . $monthly_rent . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_length_of_time_at_current_address', esc_html__( 'Length of Time at Current Address', 'cardealer-helper' ) ) . ' :' . $length_of_time_at_current_add_year . ':' . $length_of_time_at_current_add_month . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_length_of_employment', esc_html__( 'Length of Employment', 'cardealer-helper' ) ) . ' :' . $length_of_employment_year . ':' . $length_of_employment_month . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_annual_income', esc_html__( 'Annual Income', 'cardealer-helper' ) ) . ' :' . $annual_income . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_other_income_amount_monthly', esc_html__( 'Other Income Amount (Monthly)', 'cardealer-helper' ) ) . ' :' . $other_income_ammount_monthly . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_other_income_source', esc_html__( 'Other Income (Source)', 'cardealer-helper' ) ) . ' :' . $other_income_source . PHP_EOL;
						$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_include_additional_info2', esc_html__( 'Additional Information', 'cardealer-helper' ) ) . ' :' . $additional_information . PHP_EOL . PHP_EOL . PHP_EOL;

						// ADD JOINT APPLICATION DETAIL ONLY IF JOINT APPLICATION IS CHECKED INSIDE FINANCIAL FORM.
						if ( isset( $_POST['joint_application'] ) && 'on' === $_POST['joint_application'] ) {
							$customer_detail .= esc_html__( 'Joint Application Detail :', 'cardealer-helper' ) . PHP_EOL . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_first_name', esc_html__( 'First Name', 'cardealer-helper' ) ) . ' :' . $joint_first_name . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_middle_initial', esc_html__( 'Middle Initial', 'cardealer-helper' ) ) . ' :' . $joint_middle_initial . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_last_name', esc_html__( 'Last Name', 'cardealer-helper' ) ) . ' :' . sanitize_text_field( wp_unslash( $_POST['last_name_joint'] ) ) . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_relationship_to_applicant', esc_html__( 'Relationship to Applicant', 'cardealer-helper' ) ) . ' :' . sanitize_text_field( wp_unslash( $_POST['relationship_to_applicant'] ) ) . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_street_address', esc_html__( 'Street Address', 'cardealer-helper' ) ) . ' :' . $joint_street_address . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_city', esc_html__( 'City', 'cardealer-helper' ) ) . ' :' . $joint_city . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_state', esc_html__( 'State', 'cardealer-helper' ) ) . ' :' . $joint_state . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_zip', esc_html__( 'Zip', 'cardealer-helper' ) ) . ' :' . $joint_zip . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_preferred_email_address', esc_html__( 'Preferred Email Address', 'cardealer-helper' ) ) . ' :' . $joint_preferred_email_address . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_daytime_phone_number', esc_html__( 'Daytime Phone Number', 'cardealer-helper' ) ) . ' :' . $joint_daytime_phone_number . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_mobile', esc_html__( 'Mobile', 'cardealer-helper' ) ) . ' :' . $joint_mobile_phone_number . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_date_of_birth', esc_html__( 'Date of Birth', 'cardealer-helper' ) ) . ' :' . $joint_date_of_birth_month . '-' . $joint_date_of_birth_day . '-' . $joint_date_of_birth_year . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_ssn', esc_html__( 'Social Security Number (SSN)', 'cardealer-helper' ) ) . ' :' . $joint_social_security_number . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_employer_name', esc_html__( 'Employer Name', 'cardealer-helper' ) ) . ' :' . $joint_employer_name . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_employer_phone', esc_html__( 'Employer Phone', 'cardealer-helper' ) ) . ' :' . $joint_employer_phone . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_job_title', esc_html__( 'Job Title', 'cardealer-helper' ) ) . ' :' . $joint_job_title . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_living_arrangements', esc_html__( 'Living Arrangements', 'cardealer-helper' ) ) . ' :' . $joint_living_arrangements . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_monthly_rent_mortgage_payment', esc_html__( 'Monthly Rent/Mortgage Payment', 'cardealer' ) ) . ' :' . $joint_monthly_rent . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_length_of_time_at_current_address', esc_html__( 'Length of Time at Current Address', 'cardealer-helper' ) ) . ' :' . $joint_length_of_time_at_current_add_year . ':' . sanitize_text_field( wp_unslash( $_POST['length_of_time_at_current_add_month_joint'] ) ) . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_length_of_employment', esc_html__( 'Length of Employment', 'cardealer-helper' ) ) . ' :' . $joint_length_of_employment_year . ':' . $joint_length_of_employment_month . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_annual_income', esc_html__( 'Annual Income', 'cardealer-helper' ) ) . ' :' . $joint_annual_income . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_other_income_amount_monthly', esc_html__( 'Other Income Amount (Monthly)', 'cardealer-helper' ) ) . ' :' . $joint_other_income_ammount_monthly . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_other_income_source', esc_html__( 'Other Income (Source)', 'cardealer-helper' ) ) . ' :' . $joint_other_income_source . PHP_EOL;
							$customer_detail .= cardealer_get_theme_option( 'cstfrm_lbl_include_additional_info2', esc_html__( 'Additional Information', 'cardealer-helper' ) ) . ' :' . $joint_additional_information . PHP_EOL;
						}
						$adf_data .= $customer_detail . PHP_EOL;
						$adf_data .= '</comments>' . PHP_EOL;
						$adf_data .= '</customer>' . PHP_EOL;
						$adf_data .= '<vendor>' . PHP_EOL;
						$adf_data .= '<contact>' . PHP_EOL;
						$adf_data .= '<name part="full">' . $car_dealer_options['financial_form_from_name'] . '</name>' . PHP_EOL;
						$adf_data .= '</contact>' . PHP_EOL;
						$adf_data .= '</vendor>' . PHP_EOL;
						$adf_data .= '</prospect>' . PHP_EOL;
						$adf_data .= '</adf>' . PHP_EOL;
					}

					// Sending ADF Mail.
					if ( isset( $car_dealer_options['financial_form_adf_mail'] ) && 'off' !== $car_dealer_options['financial_form_adf_mail'] ) {
						if ( isset( $car_dealer_options['financial_form_adf_mail_to'] ) && ! empty( $car_dealer_options['financial_form_adf_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['financial_form_adf_mail_to'];

							// Mail body.
							$adf_headers  = $headers;
							$adf_headers .= "MIME-Version: 1.0\r\n";
							$adf_headers .= "content-type: text/plain; charset=UTF-8\r\n";
							if ( ! wp_mail( $to, $subject, $adf_data, $adf_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					// Mail body.
					$fields = array(
						'CD_FROM_NAME'                   => $from_name,
						'CD_FIRST_NAME'                  => $first_name,
						'CD_MIDDLE_INIT'                 => $middle_initial,
						'CD_LAST_NAME'                   => $last_name,
						'CD_STREET_ADD'                  => $street_address,
						'CD_CITY'                        => $city,
						'CD_STATE'                       => isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '',
						'CD_ZIP'                         => $zip,
						'CD_PREF_EMAIL_ADD'              => $preferred_email_address,
						'CD_DAYTIME_PHONE_NO'            => $daytime_phone_number,
						'CD_MOBILE_PHONE_NO'             => $mobile_phone_number,
						'CD_DATE_OF_BIRTH'               => $date_of_birth_month . '-' . $date_of_birth_day . '-' . $date_of_birth_year,
						'CD_LIVING_ARRANG'               => $living_arrangements,
						'CD_SSN'                         => $social_security_number,
						'CD_EMPLOYER_NAME'               => $employer_name,
						'CD_MONTHLY_RENT'                => $monthly_rent,
						'CD_EMPLOYER_PHONE'              => $employer_phone,
						'CD_JOB_TITLE'                   => $job_title,
						'CD_LEN_OF_TIME_AT_CUR_ADD'      => $length_of_time_at_current_add_year . ':' . $length_of_time_at_current_add_month,
						'CD_LENGTH_OF_EMP'               => $length_of_employment_year . ':' . $length_of_employment_month,
						'CD_ANNUAL_INCOME'               => $annual_income,
						'CD_OTHER_INC_AMT_MONTHLY'       => $other_income_ammount_monthly,
						'CD_OTHER_INCOME_SOURCE'         => $other_income_source,
						'CD_ADD_INFO'                    => $additional_information,
						'CD_JOINT_FIRST_NAME'            => $joint_first_name,
						'CD_JOINT_MIDDLE_INIT'           => $joint_middle_initial,
						'CD_JOINT_LAST_NAME'             => $joint_last_name,
						'CD_JOINT_REL_TO_APPLICANT'      => sanitize_text_field( wp_unslash( $_POST['relationship_to_applicant'] ) ),
						'CD_JOINT_STREET_ADD'            => $joint_street_address,
						'CD_JOINT_CITY'                  => $joint_city,
						'CD_JOINT_STATE'                 => $joint_state,
						'CD_JOINT_ZIP'                   => $joint_zip,
						'CD_JOINT_PREFERRED_EMAIL_ADD'   => $joint_preferred_email_address,
						'CD_JOINT_DAYTIME_PHONE_NO'      => $joint_daytime_phone_number,
						'CD_JOINT_MOBILE_PHONE_NO'       => $joint_mobile_phone_number,
						'CD_JOINT_DATE_OF_BIRTH'         => $joint_date_of_birth_month . '-' . $joint_date_of_birth_day . '-' . $joint_date_of_birth_year,
						'CD_JOINT_SSN'                   => $joint_social_security_number,
						'CD_JOINT_EMP_NAME'              => $joint_employer_name,
						'CD_JOINT_EMP_PHONE'             => $joint_employer_phone,
						'CD_JOINT_JOB_TITLE'             => $joint_job_title,
						'CD_JOINT_LENGTH_OF_EMP'         => $joint_length_of_employment_year . ':' . $joint_length_of_employment_month,
						'CD_JOINT_ANNUAL_INCOME'         => $joint_annual_income,
						'CD_JOINT_LIVING_ARRANG'         => $joint_living_arrangements,
						'CD_JOINT_MONTHLY_RENT'          => $joint_monthly_rent,
						'CD_JOINT_LENGTH_OF_TIME'        => $joint_length_of_time_at_current_add_year . ':' . sanitize_text_field( wp_unslash( $_POST['length_of_time_at_current_add_month_joint'] ) ),
						'CD_JOINT_OTHER_INC_AMT_MONTHLY' => $joint_other_income_ammount_monthly,
						'CD_JOINT_OTHER_INC_SOURCE'      => $joint_other_income_source,
						'CD_JOINT_ADD_INFO'              => $joint_additional_information,
					);

					// Sending HTML Mail.
					if ( isset( $car_dealer_options['financial_form_html_mail'] ) && 'off' !== $car_dealer_options['financial_form_html_mail'] ) {
						if ( isset( $car_dealer_options['financial_form_html_mail_to'] ) && ! empty( $car_dealer_options['financial_form_html_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['financial_form_html_mail_to'];

							// Mail body.
							if ( isset( $car_dealer_options['financial_form_html_body'] ) && ! empty( $car_dealer_options['financial_form_html_body'] ) ) {
								$financial_form_html_body = $car_dealer_options['financial_form_html_body'];
								$fields['PRODUCT_DETAIL'] = $product;
								foreach ( $fields as $tag => $value ) {
									if ( strpos( $financial_form_html_body, '#' . $tag . '#' ) !== false ) {
										$financial_form_html_body = str_replace( '#' . $tag . '#', $value, $financial_form_html_body );
									}
								}
							} else {
								$financial_form_html_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}
							$html_headers  = $headers;
							$html_headers .= "MIME-Version: 1.0\r\n";
							$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
							if ( ! wp_mail( $to, $subject, $financial_form_html_body, $html_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					// Sending Text Mail.
					if ( isset( $car_dealer_options['financial_form_text_mail'] ) && 'off' !== $car_dealer_options['financial_form_text_mail'] ) {
						if ( isset( $car_dealer_options['financial_form_text_mail_to'] ) && ! empty( $car_dealer_options['financial_form_text_mail_to'] ) ) {
							$mail_set     = 1;
							$dealer_email = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : $car_dealer_options['financial_form_text_mail_to'];

							// Mail body.
							if ( isset( $car_dealer_options['financial_form_text_body'] ) && ! empty( $car_dealer_options['financial_form_text_body'] ) ) {
								$financial_form_text_body = $car_dealer_options['financial_form_text_body'];

								$fields['PRODUCT_DETAIL'] = $plain_mail;
								foreach ( $fields as $tag => $value ) {
									if ( strpos( $financial_form_text_body, '#' . $tag . '#' ) !== false ) {
										$financial_form_text_body = str_replace( '#' . $tag . '#', $value, $financial_form_text_body );
									}
								}
							} else {
								$financial_form_text_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}

							$text_headers  = $headers;
							$text_headers .= "MIME-Version: 1.0\r\n";
							$text_headers .= "content-type: text/plain; charset=UTF-8\r\n";

							if ( ! wp_mail( $to, $subject, $financial_form_text_body, $text_headers ) ) {
								$mail_error = 1;
							}
						}
					}

					if ( ( isset( $car_dealer_options['financial_form_adf_mail'] ) && 'off' !== $car_dealer_options['financial_form_adf_mail'] ) || ( isset( $car_dealer_options['financial_form_html_mail'] ) && 'off' !== $car_dealer_options['financial_form_html_mail'] ) || ( isset( $car_dealer_options['financial_form_text_mail'] ) && 'off' !== $car_dealer_options['financial_form_text_mail'] ) ) {
						// If not mail is set from admin form options then mail will sent to admin( if no dealer available for this car).
						if ( 0 === (int) $mail_set ) {
							$html_headers  = $headers;
							$html_headers .= "MIME-Version: 1.0\r\n";
							$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
							$dealer_email  = cdhl_get_dealer_mail( $car_id ); // Get dealer email id.

							$to = ! empty( $dealer_email ) ? $dealer_email : get_option( 'admin_email' );
							// Mail body.
							if ( isset( $car_dealer_options['financial_form_html_body'] ) && ! empty( $car_dealer_options['financial_form_html_body'] ) ) {
								$financial_form_html_body = $car_dealer_options['financial_form_html_body'];
								$fields['PRODUCT_DETAIL'] = $product;

								foreach ( $fields as $tag => $value ) {
									if ( strpos( $financial_form_html_body, '#' . $tag . '#' ) !== false ) {
										$financial_form_html_body = str_replace( '#' . $tag . '#', $value, $financial_form_html_body );
									}
								}
							} else {
								$financial_form_text_body = esc_html__( 'One Inquiry Received', 'cardealer-helper' );
							}
							if ( ! wp_mail( $to, $subject, $financial_form_text_body, $html_headers ) ) {
								$mail_error = 1;
							}
						}

						if ( 1 === (int) $mail_error ) {
							$errors[] = esc_html__( 'Sorry there was an error sending your message. Please try again later.', 'cardealer-helper' );
						} else {
							$response_array = array(
								'status' => esc_html__( '1', 'cardealer-helper' ),
								'msg'    => '<div class="alert alert-success">' . esc_html__( 'Request sent successfully', 'cardealer-helper' ) . '</div>',
							);
						}
					} else {
						$response_array = array(
							'status' => esc_html__( '1', 'cardealer-helper' ),
							'msg'    => '<div class="alert alert-success">' . esc_html__( 'Request sent successfully', 'cardealer-helper' ) . '</div>',
						);
					}
				}
			}

			if ( ! empty( $errors ) ) {
				$err = '';
				foreach ( $errors as $error ) {
					$err .= '<p>' . $error . '</p>';
				}
				$response_array = array(
					'status' => 'error',
					'msg'    => '<div class="alert alert-danger">' . $err . '</div>',
				);
			}
		}
		echo wp_json_encode( $response_array );
		die;
	}
}
/* Get Financial Personal info */
add_filter(
	'wp_privacy_personal_data_exporters',
	'cdhl_register_financial_lead_info_exporter',
	10
);
if ( ! function_exists( 'cdhl_register_financial_lead_info_exporter' ) ) {
	/**
	 * Register financial lead info exporter
	 *
	 * @param array $exporters array variable.
	 */
	function cdhl_register_financial_lead_info_exporter( $exporters ) {
		$exporters['cardealer-helper-financial-exporter'] = array(
			'exporter_friendly_name' => esc_html__( 'Financial Lead Data', 'cardealer-helper' ),
			'callback'               => 'cdhl_gdpr_export_financial_inq_lead_info',
		);
		return $exporters;
	}
}

if ( ! function_exists( 'cdhl_gdpr_export_financial_inq_lead_info' ) ) {
	/**
	 * GDPR export financial inq lead info
	 *
	 * @param string $email_address email address.
	 */
	function cdhl_gdpr_export_financial_inq_lead_info( $email_address ) {
		if ( empty( $email_address ) ) {
			return;
		}
		return cdhl_gdpr_export_get_lead_info( $email_address, 'financial_inquiry' );
	}
}

// Erase data.
if ( ! function_exists( 'cdhl_financial_inquiry_eraser' ) ) {
	/**
	 * Financial inquiry eraser
	 *
	 * @param string $email_address email address.
	 */
	function cdhl_financial_inquiry_eraser( $email_address ) {
		if ( empty( $email_address ) ) {
			return;
		}
		$number = 500; // Limit us to avoid timing out.
		global $wpdb;

		$lead_query = "SELECT DISTINCT(cdhl_meta.post_id)
		FROM $wpdb->postmeta cdhl_meta
		JOIN $wpdb->posts cdhl_posts ON cdhl_meta.post_id = cdhl_posts.ID
		WHERE cdhl_posts.post_type = 'financial_inquiry'
		AND cdhl_meta.meta_value = '$email_address'";
		$post_ids   = $wpdb->get_results( $lead_query, ARRAY_A ); // get post ids.

		$items_removed = false;
		if ( ! empty( $post_ids ) ) {
			foreach ( $post_ids as $id ) {
				$std_post = array(
					'ID'         => $id['post_id'],
					'post_title' => esc_html__( 'Anonymous', 'cardealer-helper' ),
				);
				wp_update_post( $std_post );

				$first_name = get_post_meta( $id['post_id'], 'first_name', true );
				if ( ! empty( $first_name ) ) {
					update_post_meta( $id['post_id'], 'first_name', esc_html__( 'Anonymous', 'cardealer-helper' ) );
					$items_removed = true;
				}
				$middle_initial = get_post_meta( $id['post_id'], 'middle_initial', true );
				if ( ! empty( $middle_initial ) ) {
					update_post_meta( $id['post_id'], 'middle_initial', '' );
					$items_removed = true;
				}
				$last_name = get_post_meta( $id['post_id'], 'last_name', true );
				if ( ! empty( $last_name ) ) {
					update_post_meta( $id['post_id'], 'last_name', esc_html__( 'Anonymous', 'cardealer-helper' ) );
					$items_removed = true;
				}
				$street_address = get_post_meta( $id['post_id'], 'street_address', true );
				if ( ! empty( $street_address ) ) {
					update_post_meta( $id['post_id'], 'street_address', '' );
					$items_removed = true;
				}
				$city = get_post_meta( $id['post_id'], 'city', true );
				if ( ! empty( $city ) ) {
					update_post_meta( $id['post_id'], 'city', '' );
					$items_removed = true;
				}
				$state = get_post_meta( $id['post_id'], 'state', true );
				if ( ! empty( $state ) ) {
					update_post_meta( $id['post_id'], 'state', '' );
					$items_removed = true;
				}
				$zip = get_post_meta( $id['post_id'], 'zip', true );
				if ( ! empty( $zip ) ) {
					update_post_meta( $id['post_id'], 'zip', '' );
					$items_removed = true;
				}
				$preferred_email_address = get_post_meta( $id['post_id'], 'preferred_email_address', true );
				if ( ! empty( $preferred_email_address ) ) {
					update_post_meta( $id['post_id'], 'preferred_email_address', 'deleted@site.invalid' );
					$items_removed = true;
				}
				$daytime_phone_number = get_post_meta( $id['post_id'], 'daytime_phone_number', true );
				if ( ! empty( $daytime_phone_number ) ) {
					update_post_meta( $id['post_id'], 'daytime_phone_number', 'xxxxxxxxxx' );
					$items_removed = true;
				}
				$mobile_phone_number = get_post_meta( $id['post_id'], 'mobile_phone_number', true );
				if ( ! empty( $mobile_phone_number ) ) {
					update_post_meta( $id['post_id'], 'mobile_phone_number', 'xxxxxxxxxx' );
					$items_removed = true;
				}
				$date_of_birth = get_post_meta( $id['post_id'], 'date_of_birth', true );
				if ( ! empty( $date_of_birth ) ) {
					update_post_meta( $id['post_id'], 'date_of_birth', 'xx-xx-xxxx' );
					$items_removed = true;
				}
				$ssn = get_post_meta( $id['post_id'], 'ssn', true );
				if ( ! empty( $ssn ) ) {
					update_post_meta( $id['post_id'], 'ssn', 'xxx-xx-xxxx' );
					$items_removed = true;
				}
				$joint_first_name = get_post_meta( $id['post_id'], 'joint_first_name', true );
				if ( ! empty( $joint_first_name ) ) {
					update_post_meta( $id['post_id'], 'joint_first_name', esc_html__( 'Anonymous', 'cardealer-helper' ) );
					$items_removed = true;
				}
				$joint_middle_name = get_post_meta( $id['post_id'], 'joint_middle_name', true );
				if ( ! empty( $joint_middle_name ) ) {
					update_post_meta( $id['post_id'], 'joint_middle_name', '' );
					$items_removed = true;
				}
				$joint_last_name = get_post_meta( $id['post_id'], 'joint_last_name', true );
				if ( ! empty( $joint_last_name ) ) {
					update_post_meta( $id['post_id'], 'joint_last_name', esc_html__( 'Anonymous', 'cardealer-helper' ) );
					$items_removed = true;
				}
				$joint_street_address = get_post_meta( $id['post_id'], 'joint_street_address', true );
				if ( ! empty( $joint_street_address ) ) {
					update_post_meta( $id['post_id'], 'joint_street_address', '' );
					$items_removed = true;
				}
				$joint_city = get_post_meta( $id['post_id'], 'joint_city', true );
				if ( ! empty( $joint_city ) ) {
					update_post_meta( $id['post_id'], 'joint_city', '' );
					$items_removed = true;
				}
				$joint_state = get_post_meta( $id['post_id'], 'joint_state', true );
				if ( ! empty( $joint_state ) ) {
					update_post_meta( $id['post_id'], 'joint_state', '' );
					$items_removed = true;
				}
				$joint_zip = get_post_meta( $id['post_id'], 'joint_zip', true );
				if ( ! empty( $joint_zip ) ) {
					update_post_meta( $id['post_id'], 'joint_zip', '' );
					$items_removed = true;
				}
				$joint_preferred_email_address = get_post_meta( $id['post_id'], 'joint_preferred_email_address', true );
				if ( ! empty( $joint_preferred_email_address ) ) {
					update_post_meta( $id['post_id'], 'joint_preferred_email_address', 'deleted@site.invalid' );
					$items_removed = true;
				}
				$joint_day_time_phone_no = get_post_meta( $id['post_id'], 'joint_day_time_phone_no', true );
				if ( ! empty( $joint_day_time_phone_no ) ) {
					update_post_meta( $id['post_id'], 'joint_day_time_phone_no', 'xxxxxxxxxx' );
					$items_removed = true;
				}
				$joint_mobile_phone_number = get_post_meta( $id['post_id'], 'joint_mobile_phone_number', true );
				if ( ! empty( $joint_mobile_phone_number ) ) {
					update_post_meta( $id['post_id'], 'joint_mobile_phone_number', 'xxxxxxxxxx' );
					$items_removed = true;
				}
				$joint_date_of_birth = get_post_meta( $id['post_id'], 'joint_date_of_birth', true );
				if ( ! empty( $joint_date_of_birth ) ) {
					update_post_meta( $id['post_id'], 'joint_date_of_birth', 'xx-xx-xxxx' );
					$items_removed = true;
				}
				$joint_ssn = get_post_meta( $id['post_id'], 'joint_ssn', true );
				if ( ! empty( $joint_ssn ) ) {
					update_post_meta( $id['post_id'], 'joint_ssn', 'xxx-xx-xxxx' );
					$items_removed = true;
				}
			}
		}

		// Tell core if we have more inquiries to work on still.
		$done = count( $post_ids ) < $number;
		return array(
			'items_removed'  => $items_removed,
			'items_retained' => false, // always false in this example.
			'messages'       => array(), // no messages in this example.
			'done'           => $done,
		);
	}
}


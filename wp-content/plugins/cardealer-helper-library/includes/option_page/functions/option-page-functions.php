<?php
/**
 * Options page functions.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

/**
 * Admin alert notice functions.
 *
 * @param string  $msg      Notice message.
 * @param string  $status   Notice status.
 * @param string  $heading  Notice heading.
 * @param boolean $return   Whether to return content.
 * @return mixed
 */
function cdhl_admin_notice( $msg = '', $status = 'error', $heading = '', $return = true ) {
	$heading = ( ! empty( $heading ) && is_string( $heading ) ) ? $heading : ucfirst( $status );

	if ( $return ) {
		ob_start();
	}
	?>
	<div class="notice notice-<?php echo esc_attr( $status ); ?> cdhl-is-dismissible fade">
		<p style="line-height: 150%">
			<strong><?php echo esc_html( $heading ); ?></strong></br>
			<?php
				echo wp_kses(
					$msg,
					array(
						'br' => array(),
					)
				);
			?>
		</p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice', 'cardealer-helper' ); ?></span></button>
	</div>
	<?php
	if ( $return ) {
		return ob_get_clean();
	}
}


/**
 * Return array of additional attributes.
 *
 * @return array
 */
function cdhl_get_additional_attributes() {
	$cdhl_additional_attributes = get_option( 'cdhl_additional_attributes' );

	if ( empty( $cdhl_additional_attributes ) ) {
		$cdhl_additional_attributes = array();
	}

	return $cdhl_additional_attributes;
}


/**
 * Return array of additional attributes.
 *
 * @return array
 */
function cdhl_get_additional_attributes_obj() {
	$attributes            = array();
	$additional_attributes = cdhl_get_additional_attributes();

	$attributes = array_map( function( $value ) {
		$tax_data = get_taxonomy( $value );
		return ( $tax_data ) ? $tax_data : false;
	}, array_column( $additional_attributes, 'slug', 'slug' ) );

	// Exclude empty values.
	$attributes = array_filter( $attributes );

	return $attributes;
}


/**
 * Return html of core attributes.
 *
 * @return void
 */
function cdhl_get_core_attributes_html() {
	$core_attributes = cdhl_get_core_attributes();
	if ( ! empty( $core_attributes ) ) {
		$j = 1;
		foreach ( $core_attributes as $tax_obj ) {
			$singular_name = $tax_obj->labels->singular_name;
			$plural_name   = $tax_obj->labels->name;
			$slug          = ( isset($tax_obj->rewrite['slug']) ) ? $tax_obj->rewrite['slug'] : $tax_obj->name;
			$taxonomy      = $tax_obj->name;

			$row_classes = array(
				'row-' . $j,
				( ( $j % 2 == 0 ) ? 'row-odd' : 'row-even' ),
			);
			$edit_row_classes = array(
				'edit-core-row',
				( ( $j % 2 == 0 ) ? 'row-odd' : 'row-even' ),
			);
			?>
			<tr class="<?php cdhl_class_builder( $row_classes ); ?>">
				<td><?php echo esc_html( $singular_name ); ?></td>
				<td><?php echo esc_html( $plural_name ); ?></td>
				<td><?php echo esc_html( $slug ); ?></td>
				<td><?php echo esc_html( $taxonomy ); ?></td>
				<td>
					<a data-coreid="core-index-<?php echo esc_attr( $j ); ?>" class="edit-core-attr button button-default" href="javascript:void(0);">
						<i class="fa fa-pencil-square-o"></i>
					</a>
					<a class="view-attr button button-default" href="<?php echo admin_url( 'edit-tags.php?post_type=cars&taxonomy=' . $taxonomy ); ?>">
						<i class="fa fa-list-alt"></i>
					</a>
					<span class="spinner"></span>
				</td>
			</tr>
			<tr class="<?php cdhl_class_builder( $edit_row_classes ); ?>" id="core-index-<?php echo esc_attr( $j ); ?>" style="display:none;">
				<td colspan="5">
					<div class="">
						<label><?php esc_html_e( 'Singular name', 'cardealer-helper' ); ?>:</label>
						<input type="text" name="singular_name" id="core-singular-name-core-index-<?php echo esc_attr( $j ); ?>" class="regular-text" value="<?php echo esc_html( $singular_name ); ?>">
						<label for=""><?php esc_html_e( 'Plural name', 'cardealer-helper' ); ?>:</label>
						<input type="text" name="plural_name" id="core-plural-name-core-index-<?php echo esc_attr( $j ); ?>" class="regular-text" value="<?php echo esc_html( $plural_name ); ?>">
						<input type="submit" name="edit-core-attributes-submit" data-id="core-index-<?php echo esc_attr( $j ); ?>" data-slug="<?php echo esc_attr( $slug ); ?>" data-taxonomy="<?php echo esc_attr( $taxonomy ); ?>" class="button edit-core-attributes-submit" value="Submit">
						<span class="spinner"></span>
					</div>
				</td>
			</tr>
			<?php
			$j++;
		}
	} else {
		?>
		<tr class="row-1">
			<td colspan="5"><?php echo esc_html__( 'No data found', 'cardealer-helper' ); ?></td>
		</tr>
		<?php
	}
}

/**
 * Edit core attributes
 */
function cdhl_edit_core_attributes() {
	$response = array(
		'status' => 'error',
		'msg'    => esc_html__( 'Something went wrong!', 'cardealer-helper' ),
	);
	$error    = array();
	$heading  = esc_html__( 'One or more fields have an error. Please check and try again.', 'cardealer-helper' );

	if ( ! isset( $_POST['nonce'] ) ) {
		$response['msg'] = cdhl_admin_notice( $response['msg'], 'error', esc_html__( 'Oops!', 'cardealer-helper' ) );
		wp_send_json( $response );
		wp_die();
	}

	if ( wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'edit_core_attributes' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( ! current_user_can( 'manage_options' ) ) {
			$response['msg'] = cdhl_admin_notice(
				esc_html__( 'Sorry, you are not allowed to create attributes.', 'cardealer-helper' ),
				'error',
				esc_html__( 'You need a higher level of permission.', 'cardealer-helper' )
			);
			wp_send_json( $response );
			wp_die();
		}

		$singular_name  = ( isset( $_POST['singular_name'] ) && ! empty( $_POST['singular_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['singular_name'] ) ) : '';
		$plural_name    = ( isset( $_POST['plural_name'] ) && ! empty( $_POST['plural_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['plural_name'] ) ) : '';
		//$attribute_slug = ( isset( $_POST['attribute_slug'] ) && ! empty( $_POST['attribute_slug'] ) ) ? sanitize_text_field( wp_unslash( $_POST['attribute_slug'] ) ) : '';
		//$attribute_slug = ( ! empty( $attribute_slug ) ) ? sanitize_title( $attribute_slug ) : $attribute_slug;
		$action_mode    = ( isset( $_POST['action'] ) && 'edit_core_attributes' === $_POST['action'] ) ? 'edit' : 'add';
		$taxonomy       = ( isset( $_POST['taxonomy'] ) && ! empty( $_POST['taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) : '';
		$slug           = ( isset( $_POST['slug'] ) && ! empty( $_POST['slug'] ) ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
		if ( empty( $singular_name ) ) {
			$error[] = esc_html__( 'Please enter singular name.', 'cardealer-helper' );
		}

		if ( empty( $plural_name ) ) {
			$error[] = esc_html__( 'Please enter plural name', 'cardealer-helper' );
		}

		/*if ( 'edit' === $action_mode && empty( $attribute_slug ) ) {
			$error[] = esc_html__( 'Slug is missing', 'cardealer-helper' );
		}*/

		if ( ! empty( $error ) ) {
			$msg = implode( '</br>', $error );

			$response['msg'] = cdhl_admin_notice( $msg, 'error', $heading );

			wp_send_json( $response );
			exit();
		}

		//$singular_name_slug = sanitize_title( $singular_name );
		//$plural_name_slug   = sanitize_title( $plural_name );

		$cdhl_core_attributes_data = get_option( 'cdhl_core_attributes' );
		$cdhl_core_attributes      = array();

		$update_data = array(
			'taxonomy'      => $taxonomy,
			'slug'          => $slug,
			'singular_name' => $singular_name,
			'plural_name'   => $plural_name,
		);
		$cdhl_core_attributes_data[ $taxonomy ] = $update_data;

		$response['msg'] = cdhl_admin_notice( esc_html__( 'Core attribute updated successfully.', 'cardealer-helper' ), 'success' );
		$response['redirect'] = add_query_arg( array(
			'post_type' => 'cars',
			'page'      => 'cardealer_attributes',
			'message'   => 'core_attribute_updated',
		), admin_url( 'edit.php' ) );

		update_option( 'cdhl_core_attributes', $cdhl_core_attributes_data );

		$response['status'] = 'success';

		ob_start();
		cdhl_get_core_attributes_html();
		$response['data'] = ob_get_clean();
	}
	wp_send_json( $response );
	exit();
}
add_action( 'wp_ajax_edit_core_attributes', 'cdhl_edit_core_attributes' );

/**
 * Return html of additional attributes.
 *
 * @return void
 */
function cdhl_get_additional_attributes_html() {
	$additional_attributes = cdhl_get_additional_attributes();

	if ( ! empty( $additional_attributes ) ) {
		$i = 1;
		foreach ( $additional_attributes as $key => $attr ) {

			/*
			$class = 'dark-row';
			if ( $i % 2 == 0 )  {
				$class = 'light-row';
			}
			*/
			$row_classes = array(
				'row-' . $i,
				( ( $i % 2 == 0 ) ? 'row-odd' : 'row-even' ),
			);
			$edit_row_classes = array(
				'edit-row',
				( ( $i % 2 == 0 ) ? 'row-odd' : 'row-even' ),
			);
			?>
			<tr class="<?php cdhl_class_builder( $row_classes ); ?>">
				<td><?php echo esc_html( $attr['singular_name'] ); ?></td>
				<td><?php echo esc_html( $attr['plural_name'] ); ?></td>
				<td><?php echo esc_html( $attr['slug'] ); ?></td>
				<td><?php echo esc_html( $attr['slug'] ); ?></td>
				<td>
					<a data-id="index-<?php echo esc_attr( $i ); ?>" class="edit-additional-attr button button-default" href="javascript:void(0);">
						<i class="fa fa-pencil-square-o"></i>
					</a>
					<a class="view-attr button button-default" href="<?php echo admin_url( 'edit-tags.php?post_type=cars&taxonomy=' . $attr['slug'] ); ?>">
						<i class="fa fa-list-alt"></i>
					</a>
					<a data-id="index-<?php echo esc_attr( $i ); ?>" data-slug="<?php echo esc_html( $attr['slug'] ); ?>" data-alerttxt="<?php echo esc_html__( 'Are you sure?', 'cardealer-helper' ); ?>" class="delete-attr button button-danger" href="javascript:void(0);">
						<i class="fa fa-trash"></i>
					</a>
					<span class="spinner"></span>
				</td>
			</tr>
			<tr class="<?php cdhl_class_builder( $edit_row_classes ); ?>" id="index-<?php echo esc_attr( $i ); ?>" style="display:none;">
				<td colspan="5">
					<div class="">
						<label><?php esc_html_e( 'Singular name', 'cardealer-helper' ); ?>:</label>
						<input type="text" name="singular_name" id="singular-name-index-<?php echo esc_attr( $i ); ?>" class="regular-text" value="<?php echo esc_html( $attr['singular_name'] ); ?>">
						<label for=""><?php esc_html_e( 'Plural name', 'cardealer-helper' ); ?>:</label>
						<input type="text" name="plural_name" id="plural-name-index-<?php echo esc_attr( $i ); ?>" class="regular-text" value="<?php echo esc_html( $attr['plural_name'] ); ?>">
						<input type="submit" name="edit-additional-attributes-submit" data-id="index-<?php echo esc_attr( $i ); ?>" data-slug="<?php echo esc_attr( $attr['slug'] ); ?>" class="button edit-additional-attributes-submit" value="Submit">
						<span class="spinner"></span>
					</div>
				</td>
			</tr>
			<?php
			$i++;
		}
	} else {
		?>
		<tr class="row">
			<td colspan="5"><?php echo esc_html__( 'No data found', 'cardealer-helper' ); ?></td>
		</tr>
		<?php
	}
}

/**
 * Add/Edit additional attributes
 */
function cdhl_add_edit_additional_attributes() {
	$response = array(
		'status' => 'error',
		'msg'    => esc_html__( 'Something went wrong!', 'cardealer-helper' ),
	);
	$error    = array();
	$heading  = esc_html__( 'One or more fields have an error. Please check and try again.', 'cardealer-helper' );

	if ( ! isset( $_POST['nonce'] ) ) {
		$response['msg'] = cdhl_admin_notice( $response['msg'], 'error', esc_html__( 'Oops!', 'cardealer-helper' ) );
		wp_send_json( $response );
		wp_die();
	}

	if ( wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'add_edit_additional_attributes' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( ! current_user_can( 'manage_options' ) ) {
			$response['msg'] = cdhl_admin_notice(
				esc_html__( 'Sorry, you are not allowed to create attributes.', 'cardealer-helper' ),
				'error',
				esc_html__( 'You need a higher level of permission.', 'cardealer-helper' )
			);
			wp_send_json( $response );
			wp_die();
		}

		$singular_name            = ( isset( $_POST['singular_name'] ) && ! empty( $_POST['singular_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['singular_name'] ) ) : '';
		$plural_name              = ( isset( $_POST['plural_name'] ) && ! empty( $_POST['plural_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['plural_name'] ) ) : '';
		$attribute_slug           = ( isset( $_POST['attribute_slug'] ) && ! empty( $_POST['attribute_slug'] ) ) ? sanitize_text_field( wp_unslash( $_POST['attribute_slug'] ) ) : '';
		$attribute_slug_submitted = $attribute_slug;
		$attribute_slug           = ( ! empty( $attribute_slug ) ) ? sanitize_title( $attribute_slug ) : $attribute_slug;
		$action_mode              = ( isset( $_POST['action'] ) && 'edit_additional_attributes' === $_POST['action'] ) ? 'edit' : 'add';

		if ( empty( $singular_name ) ) {
			$error[] = esc_html__( 'Please enter singular name.', 'cardealer-helper' );
		}

		if ( empty( $plural_name ) ) {
			$error[] = esc_html__( 'Please enter plural name', 'cardealer-helper' );
		}

		if ( 'edit' === $action_mode && empty( $attribute_slug ) ) {
			$error[] = esc_html__( 'Slug is missing', 'cardealer-helper' );
		}

		if ( 'add' === $action_mode && ! empty( $attribute_slug ) && strlen( $attribute_slug ) > 32 ) {
			$error[] = esc_html__( 'Slug must be 32 characters in length.', 'cardealer-helper' );
		}

		if ( ! empty( $error ) ) {
			$msg = implode( '</br>', $error );

			$response['msg'] = cdhl_admin_notice( $msg, 'error', $heading );

			wp_send_json( $response );
			exit();
		}

		$singular_name_slug = sanitize_title( $singular_name );
		$plural_name_slug   = sanitize_title( $plural_name );

		$cdhl_additional_attributes_data = get_option( 'cdhl_additional_attributes' );
		$cdhl_additional_attributes      = array();

		if ( 'add' === $action_mode ) {

			// Get current register taxonomies.
			$cars_taxonomies = get_object_taxonomies( 'cars' );

			if ( ! empty( $attribute_slug ) && in_array( $attribute_slug, $cars_taxonomies, true ) ) {
				$error[] = sprintf(
					/* translators: %s: taxonomy slug */
					esc_html__( 'Attribute "%s" already exists.', 'cardealer-helper' ),
					esc_html( $attribute_slug )
				);
			} elseif ( in_array( $singular_name_slug, $cars_taxonomies, true ) ) {
				$error[] = sprintf(
					/* translators: %s: taxonomy slug */
					esc_html__( 'Attribute "%s" already exists.', 'cardealer-helper' ),
					esc_html( $singular_name_slug )
				);
			} elseif ( in_array( $plural_name_slug, $cars_taxonomies, true ) ) {
				$error[] = sprintf(
					/* translators: %s: taxonomy slug */
					esc_html__( 'Attribute "%s" already exists.', 'cardealer-helper' ),
					esc_html( $plural_name_slug )
				);
			}

			foreach ( $cars_taxonomies as $new_tax ) {
				$taxobj              = get_taxonomy( $new_tax );
				$singular_name_lover = strtolower($singular_name);
				$plural_name_lover   = strtolower($plural_name);

				$rewrite_slug = $taxobj->rewrite['slug'];
				if ( ! empty( $rewrite_slug ) && $attribute_slug == $rewrite_slug ) {
					$error[] = sprintf(
						/* translators: %s: taxonomy slug */
						esc_html__( 'Attribute "%s" already exists.', 'cardealer-helper' ),
						esc_html( $attribute_slug )
					);
				}

				if ( $singular_name == $taxobj->labels->singular_name) {
					$error[] = sprintf(
						/* translators: %s: taxonomy slug */
						esc_html__( 'Attribute singular name "%s" already exists.', 'cardealer-helper' ),
						esc_html( $taxobj->labels->singular_name )
					);
				} elseif ( $singular_name_lover == strtolower($taxobj->labels->name) ) {
					$error[] = sprintf(
						/* translators: %s: taxonomy slug */
						esc_html__( 'Attribute singular name "%s" already exists.', 'cardealer-helper' ),
						esc_html( $taxobj->labels->singular_name )
					);
				}

				if ( $plural_name == $taxobj->labels->name ) {
					$error[] = sprintf(
						/* translators: %s: taxonomy slug */
						esc_html__( 'Attribute plural name "%s" already exists.', 'cardealer-helper' ),
						esc_html( $taxobj->labels->name )
					);
				} elseif ( $plural_name_lover == strtolower($taxobj->labels->name) ) {
					$error[] = sprintf(
						/* translators: %s: taxonomy slug */
						esc_html__( 'Attribute plural name "%s" already exists.', 'cardealer-helper' ),
						esc_html( $taxobj->labels->name )
					);
				}
			}

			if ( ! empty( $error ) ) {
				$msg             = implode( '</br>', $error );
				$response['msg'] = cdhl_admin_notice( $msg, 'error', $heading );

				wp_send_json( $response );
				exit();
			}

			$new_attributes   = array();
			$new_attributes[] = array(
				'singular_name' => $singular_name,
				'plural_name'   => $plural_name,
				'slug'          => ! empty( $attribute_slug ) ? $attribute_slug : $singular_name_slug,
			);

			if ( ! empty( $cdhl_additional_attributes_data ) && is_array( $cdhl_additional_attributes_data ) ) {
				$cdhl_additional_attributes = array_merge(
					$cdhl_additional_attributes_data,
					$new_attributes
				);
			} else {
				$cdhl_additional_attributes = $new_attributes;
			}

			$response['msg'] = cdhl_admin_notice( esc_html__( 'Additional attribute added successfully.', 'cardealer-helper' ), 'success' );
			$response['redirect'] = add_query_arg( array(
				'post_type' => 'cars',
				'page'      => 'cardealer_attributes',
				'message'   => 'additional_attribute_added',
			), admin_url( 'edit.php' ) );

		} else {

			if ( ! empty( $cdhl_additional_attributes_data ) && is_array( $cdhl_additional_attributes_data ) ) {

				$cars_taxos = get_taxonomies( array(
					'object_type' => array(
						'cars',
					),
				), 'objects' );

				unset( $cars_taxos[ $attribute_slug_submitted ] );

				$adtnl_attr_singular_names = array_map( function( $value ) {
					return $value->labels->singular_name;
				}, $cars_taxos );

				$adtnl_attr_plural_names   = array_map( function( $value ) {
					return $value->labels->name;
				}, $cars_taxos );

				if ( in_array( $singular_name, $adtnl_attr_singular_names ) ) {
					$error[] = sprintf(
						/* translators: %s: taxonomy slug */
						esc_html__( 'Attribute singular name "%s" already exists.', 'cardealer-helper' ),
						esc_html( $singular_name )
					);
				}

				if ( in_array( $plural_name, $adtnl_attr_plural_names ) ) {
					$error[] = sprintf(
						/* translators: %s: taxonomy slug */
						esc_html__( 'Attribute plural name "%s" already exists.', 'cardealer-helper' ),
						esc_html( $plural_name )
					);
				}

				if ( ! empty( $error ) ) {
					$msg             = implode( '</br>', $error );
					$response['msg'] = cdhl_admin_notice( $msg, 'error', $heading );

					wp_send_json( $response );
					exit();
				}

				// Get index of submitted attribute.
				$additional_attr_slugs = array_column( $cdhl_additional_attributes_data, 'slug' );
				$additional_attr_index = array_search( $attribute_slug_submitted, $additional_attr_slugs );

				// Update submitted data with index.
				$cdhl_additional_attributes_data[ $additional_attr_index ] = array(
					'singular_name' => $singular_name,
					'plural_name'   => $plural_name,
					'slug'          => $attribute_slug_submitted,
				);

				$cdhl_additional_attributes = $cdhl_additional_attributes_data;
			}

			$response['msg'] = cdhl_admin_notice( esc_html__( 'Additional attribute updated successfully.', 'cardealer-helper' ), 'success' );
			$response['redirect'] = add_query_arg( array(
				'post_type' => 'cars',
				'page'      => 'cardealer_attributes',
				'message'   => 'additional_attribute_updated',
			), admin_url( 'edit.php' ) );

		}

		update_option( 'cdhl_additional_attributes', $cdhl_additional_attributes );

		$response['status'] = 'success';

		ob_start();
		cdhl_get_additional_attributes_html();
		$response['data'] = ob_get_clean();
	}
	wp_send_json( $response );
	exit();
}
add_action( 'wp_ajax_add_additional_attributes', 'cdhl_add_edit_additional_attributes' );
add_action( 'wp_ajax_edit_additional_attributes', 'cdhl_add_edit_additional_attributes' );

/**
 * Delete additional attributes
 */
function cdhl_delete_additional_attributes() {
	$response = array(
		'status' => 'error',
		'msg'    => esc_html__( 'Something went wrong!', 'cardealer-helper' ),
	);
	$heading  = esc_html__( 'One or more fields have an error. Please check and try again.', 'cardealer-helper' );
	$error    = array();

	if ( ! isset( $_POST['nonce'] ) ) {
		$response['msg'] = cdhl_admin_notice( $response['msg'], 'error', esc_html__( 'Oops!', 'cardealer-helper' ) );
		wp_send_json( $response );
		wp_die();
	}

	if ( wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'add_edit_additional_attributes' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$attribute_slug = ( isset( $_POST['attribute_slug'] ) && ! empty( $_POST['attribute_slug'] ) ) ? sanitize_text_field( wp_unslash( $_POST['attribute_slug'] ) ) : '';

		if ( empty( $attribute_slug ) ) {
			$error[] = esc_html__( 'Slug is missing', 'cardealer-helper' );
		}

		if ( ! empty( $error ) ) {
			$msg             = implode( '</br>', $error );
			$response['msg'] = cdhl_admin_notice( $msg, 'error', $heading );
			wp_send_json( $response );
			exit();
		}

		$cdhl_additional_attributes_data = get_option( 'cdhl_additional_attributes' );

		if ( ! empty( $cdhl_additional_attributes_data ) && is_array( $cdhl_additional_attributes_data ) ) {

			$old_array = $cdhl_additional_attributes_data;
			foreach ( $old_array as $key => $eattr ) {
				if ( $attribute_slug === $eattr['slug'] ) {
					unset( $cdhl_additional_attributes_data[ $key ] );
				}
			}

			$cdhl_additional_attributes = array_values( $cdhl_additional_attributes_data );

		}

		update_option( 'cdhl_additional_attributes', $cdhl_additional_attributes );

		$response['status'] = 'success';
		$response['msg']    = cdhl_admin_notice( esc_html__( 'Attribute deleted successfully.', 'cardealer-helper' ), 'success' );
		$response['redirect'] = add_query_arg( array(
			'post_type' => 'cars',
			'page'      => 'cardealer_attributes',
			'message'   => 'additional_attribute_deleted',
		), admin_url( 'edit.php' ) );

		ob_start();
		cdhl_get_additional_attributes_html();
		$response['data'] = ob_get_clean();
	}
	wp_send_json( $response );
	exit();
}
add_action( 'wp_ajax_delete_additional_attributes', 'cdhl_delete_additional_attributes' );

function cardealer_helper_add_attr_action( $actions, $screen ) {
	$taxonomies = get_object_taxonomies( 'cars' );

	if ( 'edit-tags' === $screen->base && in_array( $screen->taxonomy, $taxonomies, true ) ) {
		$actions['back-to-attributes'] = array(
			'title'      => esc_html__( 'Back to Attributes', 'cardealer-helper' ),
			'link'       => admin_url( 'edit.php?post_type=cars&page=cardealer_attributes' ),
			'attributes' => array(
				'data-taxonomy' => $screen->taxonomy,
			),
		);
	}

	return $actions;
}
add_filter( 'cardealer_page_title_actions', 'cardealer_helper_add_attr_action', 10, 2 );

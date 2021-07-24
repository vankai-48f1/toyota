<?php
/**
 * Add help tabs.
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper/guide
 * @version 1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'CDHL_Vehicles_Attributes_Guide', false ) ) {
	return new CDHL_Vehicles_Attributes_Guide();
}

/**
 * CDHL_Vehicles_Attributes_Guide Class.
 */
class CDHL_Vehicles_Attributes_Guide {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'current_screen', array( $this, 'add_tabs' ), 50 );
	}

	/**
	 * Add help tabs.
	 */
	public function add_tabs() {
		$screen     = get_current_screen();
		$taxonomies = get_object_taxonomies( 'cars' );

		$additional_attributes = cdhl_get_additional_attributes();
		$additional_attributes = array_column( $additional_attributes, 'slug' );
		$cardealer_taxonomies  = array_diff( $taxonomies, $additional_attributes ) ;

		if ( ! $screen ) {
			return;
		}

		if ( 'cars' !== $screen->post_type ) {
			return;
		}

		$screen_id = $screen->id;

		$this->sidebar_links( $screen );

		if ( 'edit' === $screen->base || ( 'edit-tags' === $screen->base && in_array( $screen->taxonomy, $cardealer_taxonomies, true ) ) ) {
			$this->tab_content_attributes( $screen );
		}

		$this->tab_content_support( $screen );

		if ( 'edit-tags' === $screen->base && isset( $screen->taxonomy ) && in_array( $screen->taxonomy, $cardealer_taxonomies, true ) ) {
			/**
			 * add_action( 'cdhl_admin_help_taxonomy_tab_{taxonomy}', 'callable_function' );
			 */
			do_action( "cdhl_admin_help_taxonomy_tab_{$screen->taxonomy}", $screen );
		}

	}

	public function sidebar_links( $screen ) {
		global $cardealer_links;

		$sidebar_links = array(
			'pgsdotcom' => array(
				'title' => $cardealer_links['pgsdotcom']['title'],
				'link'  => $cardealer_links['pgsdotcom']['link'],
			),
			'ticksy' => array(
				'title' => $cardealer_links['ticksy']['title'],
				'link'  => $cardealer_links['ticksy']['link'],
			),
			'themeforest' => array(
				'title' => $cardealer_links['themeforest']['title'],
				'link'  => $cardealer_links['themeforest']['link'],
			),
		);

		$sidebar_links = apply_filters( 'cdhl_admin_help_sidebar_links', $sidebar_links );

		$sidebar_content = '<p><strong>' . esc_html__( 'For more information:', 'cardealer-helper' ) . '</strong></p>';

		if ( is_array( $sidebar_links ) && ! empty( $sidebar_links ) ) {

			$sidebar_content .= '<ul>';

			foreach ( $sidebar_links as $sidebar_link_k => $sidebar_link_data ) {
				$sidebar_content .= '<li><a href="' . esc_url( $sidebar_link_data['link'] ) . '" target="_blank" rel="noopener">' . esc_html( $sidebar_link_data['title'] ) . '</a><li>';
			}

			$sidebar_content .= '</ul>';
		}

		$sidebar_content = apply_filters( 'cdhl_admin_help_sidebar_content', $sidebar_content );

		$screen->set_help_sidebar( $sidebar_content );
	}

	public function tab_content_support( $screen ) {
		global $cardealer_links;

		$cdhl_support_tab_content  = '';
		$cdhl_support_tab_content .= '<h2>' . esc_html__( 'Car Dealer - Help &amp; Support', 'cardealer-helper' ) . '</h2>' ;
		$cdhl_support_tab_content .= '<p>' . sprintf(
			/* translators: %s: Documentation URL */
			__( 'If you have queries or want to know more about the theme or features, and functionalities, please read our <a href="%s">documentation</a>. You will find useful resources and other details.', 'cardealer-helper' ),
			esc_url( $cardealer_links['pgsdocs']['link'] )
		) . '</p>' ;

		if ( isset( $cardealer_links['pgsforum'] ) ) {
			$cdhl_support_tab_content .= '<p>'
			. wp_kses(
				sprintf(
					/* translators: %1$s: Forum URL, %1$s: Submit Ticket URL */
					__( 'For further assistance with the theme, use our <a href="%1$s" target="_blank" rel="noopener">community forum</a>. If you still have queries, create a <a href="%2$s" target="_blank" rel="noopener">support ticket</a> at our support forum.', 'cardealer-helper' ),
					esc_url( $cardealer_links['pgsforum']['link'] ),
					esc_url( $cardealer_links['ticksy_submit_ticket']['link'] )
				),
				array(
					'a' => array(
						'href'   => true,
						'target' => true,
						'rel'    => true,
					),
				)
			)
			. '</p>';
		} else {
			$cdhl_support_tab_content .= '<p>'
			. wp_kses(
				sprintf(
					/* translators: %1$s: Forum URL, %1$s: Submit Ticket URL */
					__( 'If you still have queries, create a <a href="%1$s" target="_blank" rel="noopener">support ticket</a> at our support forum.', 'cardealer-helper' ),
					esc_url( $cardealer_links['ticksy_submit_ticket']['link'] )
				),
				array(
					'a' => array(
						'href'   => true,
						'target' => true,
						'rel'    => true,
					),
				)
			)
			. '</p>';
		}

		$cdhl_support_tab_content .= '<p>' . esc_html__( 'Before asking for help, we recommend checking the system status page to identify any problems with your configuration.', 'cardealer-helper' ) . '</p>';
		$cdhl_support_tab_content .= '<p>';
		$cdhl_support_tab_content .= sprintf(
			'<a href="%1$s" class="button button-primary" target="_blank" rel="noopener">%2$s</a>',
			admin_url( 'admin.php?page=cardealer-system-status' ),
			esc_html__( 'System status', 'cardealer-helper' )
		);

		$cdhl_support_tab_content .= sprintf(
			' <a href="%1$s" class="button" target="_blank" rel="noopener">%2$s</a>',
			esc_url( $cardealer_links['pgsdocs']['link'] ),
			esc_html__( 'Documentation', 'cardealer-helper' )
		);
		if ( isset( $cardealer_links['pgsforum'] ) ) {
			$cdhl_support_tab_content .= sprintf(
				' <a href="%1$s" class="button" target="_blank" rel="noopener">%2$s</a>',
				$cardealer_links['pgsforum']['link'],
				esc_html__( 'Community forum', 'cardealer-helper' )
			);
		}
		$cdhl_support_tab_content .= sprintf(
			' <a href="%1$s" class="button" target="_blank" rel="noopener">%2$s</a>',
			$cardealer_links['ticksy']['link'],
			esc_html__( 'Support', 'cardealer-helper' )
		);
		$cdhl_support_tab_content .= '</p>';

		$screen->add_help_tab(
			array(
				'id'      => 'cdhl_support_tab',
				'title'   => esc_html__( 'Help &amp; Support', 'cardealer-helper' ),
				'content' => $cdhl_support_tab_content,
			)
		);
	}

	public function tab_content_attributes( $screen ) {
		global $cardealer_links;

		$screen->add_help_tab(
			array(
				'id'      => 'cdhl_attributes_guide_tab',
				'title'   => esc_html__( 'Attributes Guide', 'cardealer-helper' ),
				'content' =>
					'<h2>' . esc_html__( 'Attributes Guide', 'cardealer-helper' ) . '</h2>' .
					'<p>' . esc_html__( 'The Attributes Guide will provide information on where an attribute has been used on the frontend and backend.', 'cardealer-helper' ) . '</p>' .
					'<p>' . sprintf(
						' <a href="%1$s" class="button button-primary" target="_blank" rel="noopener">%2$s</a>',
						$cardealer_links['pgsdocs_attributes_guide']['link'],
						esc_html__( 'Attributes Guide', 'cardealer-helper' )
					) . '</p>',
			)
		);
	}

}

return new CDHL_Vehicles_Attributes_Guide();

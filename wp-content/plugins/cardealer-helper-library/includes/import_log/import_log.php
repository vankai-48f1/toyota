<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * This function used for email to friend
 *
 * @author  TeamWP @Potenza Global Solutions
 * @package car-dealer-helper
 */

add_action( 'admin_menu', 'cardealer_import_log_menu' );
if ( ! function_exists( 'cardealer_import_log_menu' ) ) {
	/**
	 * Import Log Menu
	 */
	function cardealer_import_log_menu() {
		add_submenu_page( 'log-list', esc_html__( 'Import Log', 'cardealer-helper' ), esc_html__( 'Import Log', 'cardealer-helper' ), 'manage_options', 'import-log', 'cdhl_get_import_log_data' );
	}
}

if ( ! function_exists( 'cdhl_get_import_log_data' ) ) {
	/**
	 * Get imort log
	 */
	function cdhl_get_import_log_data() {
		// Datatable Style.
		wp_register_style( 'import_log_dataTable_css', trailingslashit( CDHL_URL ) . '/css/export_log/jquery.dataTables.min.css', array(), CDHL_VERSION );
		wp_enqueue_style( 'import_log_dataTable_css' );

		// Datatable Script.
		wp_register_script( 'import_log_dataTable', trailingslashit( CDHL_URL ) . 'js/export_log/jquery.dataTables.min.js', array(), '1.10.2', true );
		wp_register_script( 'import_log_dataTable_custom', trailingslashit( CDHL_URL ) . 'js/export_log/custom_dataTables.js', array(), CDHL_VERSION, true );
		wp_register_script( 'jquery-1.12.4', trailingslashit( CDHL_URL ) . 'js/export_log/jquery-1.12.4.js', array(), CDHL_VERSION, true );

		wp_enqueue_script( 'import_log_dataTable' );
		wp_enqueue_script( 'import_log_dataTable_custom' );
		wp_enqueue_script( 'cardealer-cars' );
		?>
		<div class="table_area">
			<div class="wrap">
				<div class="msg"></div>
				<h2><?php esc_html_e( 'Vehicles Import Log List', 'cardealer-helper' ); ?></h2>
				<table id="import-log" class="display" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Title', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'Records Imported', 'cardealer-helper' ); ?></th>
							<th><?php esc_html_e( 'Import Date', 'cardealer-helper' ); ?></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<?php
	}
}

add_action( 'wp_ajax_nopriv_get_import_log', 'cdhl_get_import_log' );
add_action( 'wp_ajax_get_import_log', 'cdhl_get_import_log' );

if ( ! function_exists( 'cdhl_get_import_log' ) ) {
	/**
	 * Get import log
	 */
	function cdhl_get_import_log() {
		$result                = array();
				$records_total = 101;
				// check search.
				$search = $_REQUEST['search'];
				$start  = $_REQUEST['start'];
				$length = $_REQUEST['length'];
				$term   = $search['value'];
		if ( empty( $term ) ) {
			$term = '';
		}
				$order   = isset( $_REQUEST['order'] ) ? $_REQUEST['order'] : '';
				$orderby = 'created';
				$mode    = ( ! empty( $order[0]['dir'] ) ) ? $order[0]['dir'] : 'desc';

				$result['recordsTotal']    = cdhl_import_log_count( $term );
				$result['recordsFiltered'] = cdhl_import_log_count( $term );
				$datas                     = cdhl_search_import_log( $term, $start, $length, $orderby, $mode );
				$data                      = array();
				$i                         = 0;
		if ( $datas ) {
			foreach ( $datas as $d ) {
				$up_dir_url   = wp_upload_dir();
				$data[ $i ][] = $d['title'];
				$data[ $i ][] = $d['records_imported'];
				$data[ $i ][] = $d['import_date'];
				$i++;
			}
		}
				$result['data'] = $data;
				echo json_encode( $result );
		die;

	}
}

if ( ! function_exists( 'cdhl_import_log_count' ) ) {
	/**
	 * Import log count
	 *
	 * @param array $term term of post.
	 */
	function cdhl_import_log_count( $term ) {
		global $wpdb;
		$sql             = "SELECT count(DISTINCT pm.post_id) FROM $wpdb->postmeta pm JOIN $wpdb->posts p ON (p.ID = pm.post_id) WHERE p.post_type = 'pgs_import_log'";
		$result['total'] = $wpdb->get_var( $sql );
		return $result['total'];
	}
}

if ( ! function_exists( 'cdhl_search_import_log' ) ) {
	/**
	 * Search imoprt log
	 *
	 * @param array  $term term of post.
	 * @param int    $start start value.
	 * @param int    $length count variable.
	 * @param string $orderby order by post.
	 * @param string $mode ascending or descending.
	 */
	function cdhl_search_import_log( $term, $start = 0, $length = 50, $orderby = 'created', $mode = 'asc' ) {
		if ( 'created' === (string) $orderby ) {
			$args = array(
				'post_type'      => 'pgs_import_log',
				'orderby'        => 'meta_value',
				'meta_key'       => $orderby,
				'order'          => $mode,
				'posts_per_page' => $length,
			);
		} else {
			$args = array(
				'post_type'      => 'pgs_import_log',
				'posts_per_page' => $length,
			);
		}$args     = array(
			'post_type'      => 'pgs_import_log',
			'posts_per_page' => $length,
		);
		$log_query = new WP_Query( $args );
		$result    = array();
		$cnt       = 0;
		while ( $log_query->have_posts() ) :
			$log_query->the_post();
			$result[ $cnt ]['log_id']           = get_the_ID();
			$result[ $cnt ]['title']            = get_the_title();
			$result[ $cnt ]['records_imported'] = get_post_meta( get_the_ID(), 'records_imported', true );
			$result[ $cnt ]['import_date']      = get_the_date( 'Y-m-d  H:i:s' );
			$cnt++;
		endwhile;
		return $result;
	}
}
?>

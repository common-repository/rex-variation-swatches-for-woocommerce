<?php
/**
 * @desc Triggers only when the plugin is deleted
 * from wordpress plugins menu.
 */

/**
 * @desc if uninstall.php is not call from wordpress,
 * it exists the system.
 */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$data = get_option( 'rexvs_setup_data', false );

if ( $data ) {
	$data  = unserialize( $data );
	$is_on = $data[ 'rexvs_delete_data' ];

	if ( $is_on === 'on' ) {
		delete_option( 'rexvs_setup_data' );

		$post_ids = get_posts(
			array(
				'numberposts' => -1,
				'fields'      => 'ids',
				'post_type'   => array( 'product' ),
				'post_status' => array( 'publish', 'auto-draft', 'trash', 'pending', 'draft' ),
			)
		);

		foreach ( $post_ids as $post_id ) {
			delete_post_meta( $post_id, '_attribute_values' );
		}
	}
}
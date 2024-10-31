<?php

/**
 * Class REX_Variation_Swatches_Admin_Product
 */
class REX_Variation_Swatches_Admin_Product {
	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'woocommerce_product_option_terms', array( $this, 'product_option_terms' ), 10, 2 );

		add_action( 'wp_ajax_rexvs_add_new_attribute', array( $this, 'add_new_attribute_ajax' ) );
		add_action( 'admin_footer', array( $this, 'add_attribute_term_template' ) );
	}

	/**
	 * Add selector for extra attribute types
	 *
	 * @param $taxonomy
	 * @param $index
	 */
	public function product_option_terms( $taxonomy, $index ) {
		if ( ! array_key_exists( $taxonomy->attribute_type, rex_variation_master()->types ) ) {
			return;
		}

		$taxonomy_name = wc_attribute_taxonomy_name( $taxonomy->attribute_name );
		global $thepostid;

		$product_id = isset( $_POST['post_id'] ) ? absint( sanitize_text_field($_POST['post_id']) ) : $thepostid;
		?>

		<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'rexvs' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo $index; ?>][]">
			<?php

			$all_terms = get_terms( $taxonomy_name, apply_filters( 'woocommerce_product_attribute_terms', array( 'orderby' => 'name', 'hide_empty' => false ) ) );
			if ( $all_terms ) {
				foreach ( $all_terms as $term ) {
					echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy_name, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
				}
			}
			?>
		</select>
		<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'rexvs' ); ?></button>
		<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'rexvs' ); ?></button>
		<button class="button fr plus rexvs_add_new_attribute" data-type="<?php echo $taxonomy->attribute_type ?>"><?php esc_html_e( 'Add new', 'rexvs' ); ?></button>

		<?php
	}

	/**
	 * Ajax function handles adding new attribute term
	 */
	public function add_new_attribute_ajax() {
		$nonce  = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$tax    = isset( $_POST['taxonomy'] ) ? sanitize_text_field($_POST['taxonomy']) : '';
		$type   = isset( $_POST['type'] ) ? sanitize_text_field($_POST['type']) : '';
		$name   = isset( $_POST['name'] ) ? sanitize_text_field($_POST['name']) : '';
		$slug   = isset( $_POST['slug'] ) ? sanitize_text_field($_POST['slug']) : '';
		$swatch = isset( $_POST['swatch'] ) ? sanitize_text_field($_POST['swatch']) : '';

		if ( ! wp_verify_nonce( $nonce, '_rexvs_create_attribute' ) ) {
			wp_send_json_error( esc_html__( 'Wrong request', 'rexvs' ) );
		}

		if ( empty( $name ) || empty( $swatch ) || empty( $tax ) || empty( $type ) ) {
			wp_send_json_error( esc_html__( 'Not enough data', 'rexvs' ) );
		}

		if ( ! taxonomy_exists( $tax ) ) {
			wp_send_json_error( esc_html__( 'Taxonomy is not exists', 'rexvs' ) );
		}

		if ( term_exists( sanitize_text_field($_POST['name']), sanitize_text_field($_POST['tax']) ) ) {
			wp_send_json_error( esc_html__( 'This term is exists', 'rexvs' ) );
		}

		$term = wp_insert_term( $name, $tax, array( 'slug' => $slug ) );

		if ( is_wp_error( $term ) ) {
			wp_send_json_error( $term->get_error_message() );
		} else {
			$term = get_term_by( 'id', $term['term_id'], $tax );
			update_term_meta( $term->term_id, $type, $swatch );
		}

		wp_send_json_success(
			array(
				'msg'  => esc_html__( 'Added successfully', 'rexvs' ),
				'id'   => $term->term_id,
				'slug' => $term->slug,
				'name' => $term->name,
			)
		);
	}

	/**
	 * Print HTML of modal at admin footer and add js templates
	 */
	public function add_attribute_term_template() {
		global $pagenow, $post;

		if ( $pagenow != 'post.php' || ( isset( $post ) && get_post_type( $post->ID ) != 'product' ) ) {
			return;
		}
		?>

		<!-- <div id="rexvs-modal-container" class="rexvs-modal-container">
			<div class="rexvs-modal">
				<button type="button" class="button-link media-modal-close rexvs-modal-close">
					<span class="media-modal-icon"></span></button>
				<div class="rexvs-modal-header"><h2><?php esc_html_e( 'Add new term', 'rexvs' ) ?></h2></div>
				<div class="rexvs-modal-content">
					<p class="rexvs-term-name">
						<label>
							<?php esc_html_e( 'Name', 'rexvs' ) ?>
							<input type="text" class="widefat rexvs-input" name="name">
						</label>
					</p>
					<p class="rexvs-term-slug">
						<label>
							<?php esc_html_e( 'Slug', 'rexvs' ) ?>
							<input type="text" class="widefat rexvs-input" name="slug">
						</label>
					</p>
					<div class="rexvs-term-swatch">

					</div>
					<div class="hidden rexvs-term-tax"></div>

					<input type="hidden" class="rexvs-input" name="nonce" value="<?php echo wp_create_nonce( '_rexvs_create_attribute' ) ?>">
				</div>
				<div class="rexvs-modal-footer">
					<button class="button button-secondary rexvs-modal-close"><?php esc_html_e( 'Cancel', 'rexvs' ) ?></button>
					<button class="button button-primary rexvs-new-attribute-submit"><?php esc_html_e( 'Add New', 'rexvs' ) ?></button>
					<span class="message"></span>
					<span class="spinner"></span>
				</div>
			</div>
			<div class="rexvs-modal-backdrop media-modal-backdrop"></div>
		</div> -->

		<script type="text/template" id="tmpl-rexvs-input-color">

			<label><?php esc_html_e( 'Color', 'rexvs' ) ?></label><br>
			<input type="text" class="rexvs-input rexvs-input-color" name="swatch">

		</script>

		<script type="text/template" id="tmpl-rexvs-input-image">

			<label><?php esc_html_e( 'Image', 'rexvs' ) ?></label><br>
			<div class="rexvs-term-image-thumbnail" style="float:left;margin-right:10px;">
				<img src="<?php echo esc_url( WC()->plugin_url() . '/assets/images/placeholder.png' ) ?>" width="60px" height="60px" />
			</div>
			<div style="line-height:60px;">
				<input type="hidden" class="rexvs-input rexvs-input-image rexvs-term-image" name="swatch" value="" />
				<button type="button" class="rexvs-upload-image-button button"><?php esc_html_e( 'Upload/Add image', 'rexvs' ); ?></button>
				<button type="button" class="rexvs-remove-image-button button hidden"><?php esc_html_e( 'Remove image', 'rexvs' ); ?></button>
			</div>

		</script>

		<script type="text/template" id="tmpl-rexvs-input-label">

			<label>
				<?php esc_html_e( 'Label', 'rexvs' ) ?>
				<input type="text" class="widefat rexvs-input rexvs-input-label" name="swatch">
			</label>

		</script>

		<script type="text/template" id="tmpl-rexvs-input-tax">

			<input type="hidden" class="rexvs-input" name="taxonomy" value="{{data.tax}}">
			<input type="hidden" class="rexvs-input" name="type" value="{{data.type}}">

		</script>
		<?php
	}
}

new REX_Variation_Swatches_Admin_Product();

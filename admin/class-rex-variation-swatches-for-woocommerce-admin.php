<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/admin
 * @author     RexTheme <sakib@coderex.co>
 */
class REX_Variation_Swatches_For_Woocommerce_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version )
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Variation_Swatches_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Variation_Swatches_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		if ( $screen->id == "toplevel_page_rexvs" ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rex-variation-swatches-for-woocommerce-admin.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'rexvs-admin', plugin_dir_url( __FILE__ ) . 'css/primary.css', array( 'wp-color-picker' ), '20160615' );
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Variation_Swatches_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Variation_Swatches_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		if ( $screen->id == "toplevel_page_rexvs" ) {
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rex-variation-swatches-for-woocommerce-admin.js', array( 'jquery', 'wp-color-picker', 'wp-util' ), $this->version, false );
			wp_enqueue_script( 'main', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), $this->version, true );
			wp_localize_script(
				$this->plugin_name, 'rexvs_obj', array(
					'ajaxurl'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'rexvs' ),
				)
			);
		}

		if ( strpos( $screen->id, 'edit-pa_' ) === false && strpos( $screen->id, 'product' ) === false ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_script( 'rexvs-admin', plugin_dir_url( __FILE__ ) . 'js/primary.js', array( 'jquery', 'wp-color-picker', 'wp-util' ), '20170113', true );
		wp_localize_script(
			'rexvs-admin',
			'rexvs',
			array(
				'i18n'        => array(
					'mediaTitle'  => esc_html__( 'Choose an image', 'rexvs' ),
					'mediaButton' => esc_html__( 'Use image', 'rexvs' ),
				),
				'placeholder' => WC()->plugin_url() . '/assets/images/placeholder.png'
			)
		);
	}

	/**
	 * Init hooks for adding fields to attribute screen
	 * Save new term meta
	 * Add thumbnail column for attribute term
	 */
	public function init_attribute_hooks()
	{
		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( empty( $attribute_taxonomies ) ) {
				return;
			}

			foreach ( $attribute_taxonomies as $tax ) {
				add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array( $this, 'add_attribute_fields' ) );
				add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10, 2 );

				add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array( $this, 'add_attribute_columns' ) );
				add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array( $this, 'add_attribute_column_content' ), 10, 3 );
			}

			add_action( 'created_term', array( $this, 'save_term_meta' ), 10, 2 );
			add_action( 'edit_term', array( $this, 'save_term_meta' ), 10, 2 );
		}
	}

	/**
	 * Create hook to add fields to add attribute term screen
	 *
	 * @param string $taxonomy
	 */
	public function add_attribute_fields( $taxonomy )
	{
		$attr = rex_variation_master()->get_tax_attribute( $taxonomy );

		do_action( 'rexvs_product_attribute_field', $attr->attribute_type, '', 'add' );
	}

	/**
	 * Create hook to fields to edit attribute term screen
	 *
	 * @param object $term
	 * @param string $taxonomy
	 */
	public function edit_attribute_fields( $term, $taxonomy )
	{
		$attr  = rex_variation_master()->get_tax_attribute( $taxonomy );
		$value = get_term_meta( $term->term_id, $attr->attribute_type, true );
		do_action( 'rexvs_product_attribute_field', $attr->attribute_type, $value, 'edit' );
	}

	/**
	 * Print HTML of custom fields on attribute term screens
	 *
	 * @param $type
	 * @param $value
	 * @param $form
	 */
	public function attribute_fields( $type, $value, $form )
	{
		// Return if this is a default attribute type
		if ( in_array( $type, array( 'select', 'text' ) ) ) {
			return;
		}

		// Print the open tag of field container
		printf(
			'<%s class="form-field">%s<label for="term-%s">%s</label>%s',
			'edit' == $form ? 'tr' : 'div',
			'edit' == $form ? '<th>' : '',
			esc_attr( $type ),
			rex_variation_master()->types[ $type ],
			'edit' == $form ? '</th><td>' : ''
		);

		switch ( $type ) {
			case 'image':
				$image = $value ? wp_get_attachment_image_src( $value ) : '';
				$image = $image ? $image[ 0 ] : WC()->plugin_url() . '/assets/images/placeholder.png';
				?>
                <div class="rexvs-term-image-thumbnail" style="float:left;margin-right:10px;">
                    <img src="<?php
					echo esc_url( $image ) ?>" width="60px" height="60px"/>
                </div>
                <div style="line-height:60px;">
                    <input type="hidden" class="rexvs-term-image" name="image" value="<?php
					echo esc_attr( $value ) ?>"/>
                    <button type="button" class="rexvs-upload-image-button button"><?php
						esc_html_e( 'Upload/Add image', 'rexvs' ); ?></button>
                    <button type="button" class="rexvs-remove-image-button button <?php
					echo $value ? '' : 'hidden' ?>"><?php
						esc_html_e( 'Remove image', 'rexvs' ); ?></button>
                </div>
				<?php
				break;

			default:
				?>
                <input type="text" id="term-<?php
				echo esc_attr( $type ) ?>" name="<?php
				echo esc_attr( $type ) ?>" value="<?php
				echo esc_attr( $value ) ?>"/>
				<?php
				break;
		}

		// Print the close tag of field container
		echo 'edit' == $form ? '</td></tr>' : '</div>';
	}

	/**
	 * Save term meta
	 *
	 * @param int $term_id
	 * @param int $tt_id
	 */
	public function save_term_meta( $term_id, $tt_id ) {
	    foreach ( rex_variation_master()->types as $type => $label ) {
			if ( isset( $_POST[ $type ] ) ) {
				update_term_meta( $term_id, $type, sanitize_text_field( $_POST[ $type ] ) );
			}
		}
	}

	/**
	 * Add thumbnail column to column list
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public function add_attribute_columns( $columns ){
		$new_columns            = array();
	    
	    if ( !empty( $columns ) && isset( $columns[ 'cb' ] )) {
		    $new_columns[ 'cb' ]    = $columns[ 'cb' ];
		    $new_columns[ 'thumb' ] = '';
		    unset( $columns[ 'cb' ] );
	    }

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Render thumbnail HTML depend on attribute type
	 *
	 * @param $columns
	 * @param $column
	 * @param $term_id
	 */
	public function add_attribute_column_content( $columns, $column, $term_id )
	{
		$attr  = rex_variation_master()->get_tax_attribute( $_REQUEST[ 'taxonomy' ] );
		$value = get_term_meta( $term_id, $attr->attribute_type, true );

		switch ( $attr->attribute_type ) {
			case 'color':
				printf( '<div class="swatch-preview swatch-color" style="background-color:%s;"></div>', esc_attr( $value ) );
				break;

			case 'image':
				$image = $value ? wp_get_attachment_image_src( $value ) : '';
				$image = $image ? $image[ 0 ] : WC()->plugin_url() . '/assets/images/placeholder.png';
				printf( '<img class="swatch-preview swatch-image" src="%s" width="44px" height="44px">', esc_url( $image ) );
				break;

			case 'label':
				printf( '<div class="swatch-preview swatch-label">%s</div>', esc_html( $value ) );
				break;
		}
	}

}
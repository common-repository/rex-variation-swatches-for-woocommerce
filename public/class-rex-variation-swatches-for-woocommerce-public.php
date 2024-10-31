<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/public
 * @author     RexTheme <sakib@coderex.co>
 */
class REX_Variation_Swatches_For_Woocommerce_Public
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
	 * @param $html
	 * @param $term
	 * @param $type
	 * @param $args
	 * @return mixed|string
	 *
	 * @desc prepares and returns swatches for custom attributes.
	 */
	private $first_run = true;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( "wp_ajax_rexvs_get_product_variations", array( $this, "rexvs_get_product_variations" ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		$rexvs_setup_data = unserialize( get_option( 'rexvs_setup_data' ) );

		if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/variation-swatches-for-woocommerce-public.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		if ( is_single() || is_shop() ) {
			wp_enqueue_script( 'jquery' );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rex-variation-swatches-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
			
			wp_enqueue_script( 'rexvs-frontend', plugin_dir_url( __FILE__ ) . 'js/vs-select-frontface-quickview.js', array( 'jquery' ) );
		}
	}

	/**
	 * @param $html
	 * @param $args
	 * @return mixed|string
	 *
	 * @desc Filters function to add swatches bellow the default selector
	 */
	public function get_swatch_html( $html, $args )
	{
		$post_id          = get_the_ID();
		$rexvs_setup_data = unserialize( get_option( 'rexvs_setup_data' ) );
		$swatch_types     = rex_variation_master()->types;
		$attr             = rex_variation_master()->get_tax_attribute( $args[ 'attribute' ] );//get global attr
		$custom_attr      = get_post_meta( $post_id, '_attribute_values', true );             //get custom atributes

		$is_premium = get_option( 'rexvs_is_premium' ) ? get_option( 'rexvs_is_premium' ) : '';

		/**
		 * @desc working with both custom and global attributes.
		 */
		if ( empty( $attr ) && $is_premium === 'yes' ) {
			/**
			 * @desc if custom attributes found.
			 */
			if ( !empty( $custom_attr ) ) {
				//check i fswitcher is on or not
				$data            = get_option( 'rexvs_setup_data', false );
				$data_arr        = unserialize( $data );
				$is_on           = $data_arr[ 'rexvs_enable_customAttribute' ];
				$custom_swatches = '';
				$attribute       = $args[ 'attribute' ];//name of parent attr
//				$class            = "variation-selector variation-select-{$args['attribute']}";
				$custom_attr_type = $custom_attr[ $attribute ][ 'type' ];

				/**
				 * @desc swatch for color type
				 */
				if ( $custom_attr_type == 'color' ) {
					$options = $args[ 'options' ];                   //child attr of this attr
					$terms   = $custom_attr[ $attribute ][ 'terms' ];//all childs of parent attribute

					foreach ( $terms as $term ) {
						if ( in_array( $term[ 'slug' ], $options ) ) {
							$custom_swatches .= apply_filters( 'rexvs_custom_swatch_html', '', $term, $custom_attr[ $attribute ][ 'type' ], $args );
						}
					}

					if ( !empty( $custom_swatches ) ) {
						$class           = "variation-selector variation-select-{$args['attribute']}";
						$class           .= ' hidden';
						$custom_swatches = '<div class="rexvs-swatches" data-attribute_name="attribute_' . $args[ 'attribute' ] . '">' . $custom_swatches . '</div>';
						$html            = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $custom_swatches;

						//===dynamic Style===//
						$html .= '<style>';
						if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {
							if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] != 'on' ) {

								if ( isset( $rexvs_setup_data[ 'rexvs_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_tooltip' ] == 'on' ) {
									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                    }
                                ';
								}

								//----swatches rounded/square style------
								if ( isset( $rexvs_setup_data[ 'rexvs_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_shape_style' ] == 'squared' ) {
									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image img,
                                    .rexvs-variations .rexvs-swatches .swatch:before,
                                    .rexvs-variations .rexvs-swatches .swatch{
                                        border-radius: 0;
                                    }
                                ';
								}

								//----swatches style------
								if ( isset( $rexvs_setup_data[ 'rexvs_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shape_height' ] ) ) {
									$height = $rexvs_setup_data[ 'rexvs_shape_height' ];
								}
								else {
									$height = 40;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shape_width' ] ) ) {
									$width = $rexvs_setup_data[ 'rexvs_shape_width' ];
								}
								else {
									$width = 40;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) ) {
									$font_size = $rexvs_setup_data[ 'rexvs_swatches_font_size' ];
								}
								else {
									$font_size = 10;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) ) {
									$swatches_bg_color = $rexvs_setup_data[ 'rexvs_swatches_bg_color' ];
								}
								else {
									$swatches_bg_color = '#dddddd';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) ) {
									$swatches_color = $rexvs_setup_data[ 'rexvs_swatches_color' ];
								}
								else {
									$swatches_color = '#222222';
								}

								//--------hover background color---------
								$swatches_hvr_bg_color = '';
								$swatches_hvr_color    = '';

								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) ) {
									$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) ) {
									$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ];
								}

								$html .= '
                                .rexvs-variations .rexvs-swatches .swatch{
                                    height: ' . $height . 'px;
                                    width: ' . $width . 'px;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                    background-color: transparent;
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:hover img {
                                    opacity: 0.4;
                                }
                            ';

								//----swatches border style------
								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_swatches_border' ] == 'on' ) {

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) ) {
										$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_swatches_border_size' ];
									}
									else {
										$rexvs_swatches_border_size = 1;
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) ) {
										$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_swatches_border_style' ];
									}
									else {
										$rexvs_swatches_border_style = 'solid';
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) ) {
										$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_swatches_border_color' ];
									}
									else {
										$rexvs_swatches_border_color = '#333';
									}

									//-----selected border-width and border-color---
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) ) {
										$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) ) {
										$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ];
									}

									//-----hover border-width and border-color---
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) ) {
										$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) ) {
										$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ];
									}

									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
								}

								//----selected swatches style------
								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) ) {
									$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ];
								}
								else {
									$rexvs_seltd_swatches_bg_color = '#444';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) ) {
									$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ];
								}
								else {
									$rexvs_seltd_swatches_color = '#fff';
								}

								$html .= '
                                .rexvs-variations .rexvs-swatches .swatch:before {
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch:after {
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:before {
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                    opacity: 0.4;
                                }
                            ';

							}//--end  rexvs_individual_attr_style condition--


							//-----clear button style----
							$clr_btn_height   = '';
							$clr_btn_width    = '';
							$clr_btn_fnt_size = '';
							$clr_btn_radius   = '';
							$clr_btn_bg_color = '';
							$clr_btn_color    = '';

							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_height' ] ) ) {
								$clr_btn_height = $rexvs_setup_data[ 'rexvs_clr_btn_height' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_width' ] ) ) {
								$clr_btn_width = $rexvs_setup_data[ 'rexvs_clr_btn_width' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ] ) ) {
								$clr_btn_fnt_size = $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_radius' ] ) ) {
								$clr_btn_radius = $rexvs_setup_data[ 'rexvs_clr_btn_radius' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ] ) ) {
								$clr_btn_bg_color = $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_color' ] ) ) {
								$clr_btn_color = $rexvs_setup_data[ 'rexvs_clr_btn_color' ];
							}
							$html .= '
                            ul.products .rexvs-variations .reset_variations,
                            .rexvs-variations .reset_variations {
                                width: ' . $clr_btn_width . 'px;
                                height: ' . $clr_btn_height . 'px;
                                line-height: ' . $clr_btn_height . 'px;
                                font-size: ' . $clr_btn_fnt_size . 'px!important;
                                background-color: ' . $clr_btn_bg_color . ';
                                color: ' . $clr_btn_color . ';
                                border-radius: ' . $clr_btn_radius . 'px;
                                
                            }
                            
                        ';

							//----shop page style-----
							if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

								$shop_swatch_hover_bg    = '';
								$shop_swatch_hover_color = '';
								$shop_swatch_seltd_bg    = '';
								$shop_swatch_seltd_color = '';

								if ( isset( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ] ) ) {
									$shop_swatch_hover_bg = $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ] ) ) {
									$shop_swatch_hover_color = $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ];
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ] ) ) {
									$shop_swatch_seltd_bg = $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ] ) ) {
									$shop_swatch_seltd_color = $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ];
								}

								$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch:hover{
                                    background-color: ' . $shop_swatch_hover_bg . ';
                                    color: ' . $shop_swatch_hover_color . ';
                                }
                                ul.products .rexvs-variations .rexvs-swatches .swatch:before {
                                    background-color: ' . $shop_swatch_seltd_bg . ';
                                    color: ' . $shop_swatch_seltd_color . ';
                                }
                                ul.products .rexvs-variations .rexvs-swatches .swatch:after {
                                    border-color: ' . $shop_swatch_seltd_color . ';
                                }
                            ';
							}

						}//--end rexvs_disable_stylesheet condition--

						$html .= '</style>';
						//===dynamic Style===//

					}

					return $html;
				}
				/**
				 * @desc swatch for label type
				 */
				if ( $custom_attr_type == 'label' ) {
					$options = $args[ 'options' ];                   //child attr of this attr
					$terms   = $custom_attr[ $attribute ][ 'terms' ];//all childs of parent attribute

					foreach ( $terms as $term ) {
						if ( in_array( $term[ 'slug' ], $options ) ) {
							$custom_swatches .= apply_filters( 'rexvs_custom_swatch_html', '', $term, $custom_attr[ $attribute ][ 'type' ], $args );
						}
					}

					if ( !empty( $custom_swatches ) ) {
						$class           = "variation-selector variation-select-{$args['attribute']}";
						$class           .= ' hidden';
						$custom_swatches = '<div class="rexvs-swatches" data-attribute_name="attribute_' . $args[ 'attribute' ] . '">' . $custom_swatches . '</div>';
						$html            = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $custom_swatches;
						//===dynamic Style===//
						$html .= '<style>';
						if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {
							if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] != 'on' ) {

								if ( isset( $rexvs_setup_data[ 'rexvs_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_tooltip' ] == 'on' ) {
									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                    }
                                ';
								}

								//----swatches rounded/square style------
								if ( isset( $rexvs_setup_data[ 'rexvs_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_shape_style' ] == 'squared' ) {
									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image img,
                                    .rexvs-variations .rexvs-swatches .swatch:before,
                                    .rexvs-variations .rexvs-swatches .swatch{
                                        border-radius: 0;
                                    }
                                ';
								}

								//----swatches style------
								if ( isset( $rexvs_setup_data[ 'rexvs_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shape_height' ] ) ) {
									$height = $rexvs_setup_data[ 'rexvs_shape_height' ];
								}
								else {
									$height = 40;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shape_width' ] ) ) {
									$width = $rexvs_setup_data[ 'rexvs_shape_width' ];
								}
								else {
									$width = 40;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) ) {
									$font_size = $rexvs_setup_data[ 'rexvs_swatches_font_size' ];
								}
								else {
									$font_size = 10;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) ) {
									$swatches_bg_color = $rexvs_setup_data[ 'rexvs_swatches_bg_color' ];
								}
								else {
									$swatches_bg_color = '#dddddd';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) ) {
									$swatches_color = $rexvs_setup_data[ 'rexvs_swatches_color' ];
								}
								else {
									$swatches_color = '#222222';
								}

								//--------hover background color---------
								$swatches_hvr_bg_color = '';
								$swatches_hvr_color    = '';

								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) ) {
									$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) ) {
									$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ];
								}

								$html .= '
                                .rexvs-variations .rexvs-swatches .swatch{
                                    height: ' . $height . 'px;
                                    width: ' . $width . 'px;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                    background-color: transparent;
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:hover img {
                                    opacity: 0.4;
                                }
                            ';

								//----swatches border style------
								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_swatches_border' ] == 'on' ) {

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) ) {
										$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_swatches_border_size' ];
									}
									else {
										$rexvs_swatches_border_size = 1;
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) ) {
										$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_swatches_border_style' ];
									}
									else {
										$rexvs_swatches_border_style = 'solid';
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) ) {
										$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_swatches_border_color' ];
									}
									else {
										$rexvs_swatches_border_color = '#333';
									}

									//-----selected border-width and border-color---
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) ) {
										$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) ) {
										$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ];
									}

									//-----hover border-width and border-color---
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) ) {
										$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) ) {
										$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ];
									}

									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
								}

								//----selected swatches style------
								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) ) {
									$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ];
								}
								else {
									$rexvs_seltd_swatches_bg_color = '#444';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) ) {
									$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ];
								}
								else {
									$rexvs_seltd_swatches_color = '#fff';
								}

								$html .= '
                                .rexvs-variations .rexvs-swatches .swatch:before {
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch:after {
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:before {
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                    opacity: 0.4;
                                }
                            ';

							}//--end  rexvs_individual_attr_style condition--


							//-----clear button style----
							$clr_btn_height   = '';
							$clr_btn_width    = '';
							$clr_btn_fnt_size = '';
							$clr_btn_radius   = '';
							$clr_btn_bg_color = '';
							$clr_btn_color    = '';

							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_height' ] ) ) {
								$clr_btn_height = $rexvs_setup_data[ 'rexvs_clr_btn_height' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_width' ] ) ) {
								$clr_btn_width = $rexvs_setup_data[ 'rexvs_clr_btn_width' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ] ) ) {
								$clr_btn_fnt_size = $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_radius' ] ) ) {
								$clr_btn_radius = $rexvs_setup_data[ 'rexvs_clr_btn_radius' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ] ) ) {
								$clr_btn_bg_color = $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_color' ] ) ) {
								$clr_btn_color = $rexvs_setup_data[ 'rexvs_clr_btn_color' ];
							}
							$html .= '
                            ul.products .rexvs-variations .reset_variations,
                            .rexvs-variations .reset_variations {
                                width: ' . $clr_btn_width . 'px;
                                height: ' . $clr_btn_height . 'px;
                                line-height: ' . $clr_btn_height . 'px;
                                font-size: ' . $clr_btn_fnt_size . 'px!important;
                                background-color: ' . $clr_btn_bg_color . ';
                                color: ' . $clr_btn_color . ';
                                border-radius: ' . $clr_btn_radius . 'px;
                            }
                        ';

							//----shop page style-----
							if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

								$shop_swatch_hover_bg    = '';
								$shop_swatch_hover_color = '';
								$shop_swatch_seltd_bg    = '';
								$shop_swatch_seltd_color = '';

								if ( isset( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ] ) ) {
									$shop_swatch_hover_bg = $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ] ) ) {
									$shop_swatch_hover_color = $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ];
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ] ) ) {
									$shop_swatch_seltd_bg = $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ] ) ) {
									$shop_swatch_seltd_color = $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ];
								}

								$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch:hover{
                                    background-color: ' . $shop_swatch_hover_bg . ';
                                    color: ' . $shop_swatch_hover_color . ';
                                }
                                ul.products .rexvs-variations .rexvs-swatches .swatch:before {
                                    background-color: ' . $shop_swatch_seltd_bg . ';
                                    color: ' . $shop_swatch_seltd_color . ';
                                }
                                ul.products .rexvs-variations .rexvs-swatches .swatch:after {
                                    border-color: ' . $shop_swatch_seltd_color . ';
                                }
                            ';
							}

						}//--end rexvs_disable_stylesheet condition--

						$html .= '</style>';
						//===dynamic Style===//
					}

					return $html;
				}
				/**
				 * @desc swatch for image type
				 */
				if ( $custom_attr_type == 'image' ) {
					$options = $args[ 'options' ];
					$terms   = $custom_attr[ $attribute ][ 'terms' ];//all childs of parent attribute

					foreach ( $terms as $term ) {
						if ( in_array( $term[ 'slug' ], $options ) ) {
							$custom_swatches .= apply_filters( 'rexvs_custom_swatch_html', '', $term, $custom_attr[ $attribute ][ 'type' ], $args );
						}
					}

					if ( !empty( $custom_swatches ) ) {
						$class           = "variation-selector variation-select-{$args['attribute']}";
						$class           .= ' hidden';
						$custom_swatches = '<div class="rexvs-swatches" data-attribute_name="attribute_' . $args[ 'attribute' ] . '">' . $custom_swatches . '</div>';
						$html            = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $custom_swatches;
						//===dynamic Style===//
						$html .= '<style>';
						if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {
							if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] != 'on' ) {

								if ( isset( $rexvs_setup_data[ 'rexvs_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_tooltip' ] == 'on' ) {
									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                    }
                                ';
								}

								//----swatches rounded/square style------
								if ( isset( $rexvs_setup_data[ 'rexvs_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_shape_style' ] == 'squared' ) {
									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image img,
                                    .rexvs-variations .rexvs-swatches .swatch:before,
                                    .rexvs-variations .rexvs-swatches .swatch{
                                        border-radius: 0;
                                    }
                                ';
								}

								//----swatches style------
								if ( isset( $rexvs_setup_data[ 'rexvs_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shape_height' ] ) ) {
									$height = $rexvs_setup_data[ 'rexvs_shape_height' ];
								}
								else {
									$height = 40;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shape_width' ] ) ) {
									$width = $rexvs_setup_data[ 'rexvs_shape_width' ];
								}
								else {
									$width = 40;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) ) {
									$font_size = $rexvs_setup_data[ 'rexvs_swatches_font_size' ];
								}
								else {
									$font_size = 10;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) ) {
									$swatches_bg_color = $rexvs_setup_data[ 'rexvs_swatches_bg_color' ];
								}
								else {
									$swatches_bg_color = '#dddddd';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) ) {
									$swatches_color = $rexvs_setup_data[ 'rexvs_swatches_color' ];
								}
								else {
									$swatches_color = '#222222';
								}

								//--------hover background color---------
								$swatches_hvr_bg_color = '';
								$swatches_hvr_color    = '';

								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) ) {
									$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) ) {
									$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ];
								}

								$html .= '
                                .rexvs-variations .rexvs-swatches .swatch{
                                    height: ' . $height . 'px;
                                    width: ' . $width . 'px;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                    background-color: transparent;
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:hover img {
                                    opacity: 0.4;
                                }
                            ';

								//----swatches border style------
								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_swatches_border' ] == 'on' ) {

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) ) {
										$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_swatches_border_size' ];
									}
									else {
										$rexvs_swatches_border_size = 1;
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) ) {
										$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_swatches_border_style' ];
									}
									else {
										$rexvs_swatches_border_style = 'solid';
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) ) {
										$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_swatches_border_color' ];
									}
									else {
										$rexvs_swatches_border_color = '#333';
									}

									//-----selected border-width and border-color---
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) ) {
										$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) ) {
										$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ];
									}

									//-----hover border-width and border-color---
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) ) {
										$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) ) {
										$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ];
									}

									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
								}

								//----selected swatches style------
								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) ) {
									$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ];
								}
								else {
									$rexvs_seltd_swatches_bg_color = '#444';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) ) {
									$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ];
								}
								else {
									$rexvs_seltd_swatches_color = '#fff';
								}

								$html .= '
                                .rexvs-variations .rexvs-swatches .swatch:before {
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch:after {
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:before {
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                    opacity: 0.4;
                                }
                            ';

							}//--end  rexvs_individual_attr_style condition--


							//-----clear button style----
							$clr_btn_height   = '';
							$clr_btn_width    = '';
							$clr_btn_fnt_size = '';
							$clr_btn_radius   = '';
							$clr_btn_bg_color = '';
							$clr_btn_color    = '';

							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_height' ] ) ) {
								$clr_btn_height = $rexvs_setup_data[ 'rexvs_clr_btn_height' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_width' ] ) ) {
								$clr_btn_width = $rexvs_setup_data[ 'rexvs_clr_btn_width' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ] ) ) {
								$clr_btn_fnt_size = $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_radius' ] ) ) {
								$clr_btn_radius = $rexvs_setup_data[ 'rexvs_clr_btn_radius' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ] ) ) {
								$clr_btn_bg_color = $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_color' ] ) ) {
								$clr_btn_color = $rexvs_setup_data[ 'rexvs_clr_btn_color' ];
							}
							$html .= '
                            ul.products .rexvs-variations .reset_variations,
                            .rexvs-variations .reset_variations {
                                width: ' . $clr_btn_width . 'px;
                                height: ' . $clr_btn_height . 'px;
                                line-height: ' . $clr_btn_height . 'px;
                                font-size: ' . $clr_btn_fnt_size . 'px!important;
                                background-color: ' . $clr_btn_bg_color . ';
                                color: ' . $clr_btn_color . ';
                                border-radius: ' . $clr_btn_radius . 'px;
                            }
                        ';

							//----shop page style-----
							if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

								$shop_swatch_hover_bg    = '';
								$shop_swatch_hover_color = '';
								$shop_swatch_seltd_bg    = '';
								$shop_swatch_seltd_color = '';

								if ( isset( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ] ) ) {
									$shop_swatch_hover_bg = $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ] ) ) {
									$shop_swatch_hover_color = $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ];
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ] ) ) {
									$shop_swatch_seltd_bg = $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ] ) ) {
									$shop_swatch_seltd_color = $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ];
								}

								$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch:hover{
                                    background-color: ' . $shop_swatch_hover_bg . ';
                                    color: ' . $shop_swatch_hover_color . ';
                                }
                                ul.products .rexvs-variations .rexvs-swatches .swatch:before {
                                    background-color: ' . $shop_swatch_seltd_bg . ';
                                    color: ' . $shop_swatch_seltd_color . ';
                                }
                                ul.products .rexvs-variations .rexvs-swatches .swatch:after {
                                    border-color: ' . $shop_swatch_seltd_color . ';
                                }
                            ';
							}

						}//--end rexvs_disable_stylesheet condition--

						$html .= '</style>';
						//===dynamic Style===//
					}

				}
				/**
				 * @desc swatch for default type
				 */
				if ( $custom_attr_type == 'default' ) {
					$options = $args[ 'options' ];                   //child attr of this attr
					$terms   = $custom_attr[ $attribute ][ 'terms' ];//all childs of parent attribute
					foreach ( $terms as $term ) {
						if ( in_array( $term[ 'slug' ], $options ) ) {
							$custom_swatches .= apply_filters( 'rexvs_custom_swatch_html', '', $term, $custom_attr[ $attribute ][ 'type' ], $args );
						}
					}
					if ( !empty( $custom_swatches ) ) {
						if ( isset( $rexvs_setup_data[ 'rexvs_default_dropdown_to_button' ] ) && $rexvs_setup_data[ 'rexvs_default_dropdown_to_button' ] == 'on' ) {
							$class    = "variation-selector variation-select-{$args['attribute']}";
							$class    .= ' hidden';
							$swatches = '<div class="rexvs-swatches" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $custom_swatches . '</div>';
							$html     = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $swatches;
							//===dynamic Style for default dropdown===//
							$html .= '<style>';
							if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

								if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] != 'on' ) {
									if ( isset( $rexvs_setup_data[ 'rexvs_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_tooltip' ] == 'on' ) {
										$html .= '
                                        .rexvs-variations .rexvs-swatches .swatch .cv-tooltip{
                                            display: block;
                                            font-size : ' . $rexvs_setup_data[ 'rexvs_tooltip_fnt_size' ] . 'px;
                                            color: ' . $rexvs_setup_data[ 'rexvs_tooltip_color' ] . ';
                                            background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                        }
                                        .rexvs-variations .rexvs-swatches .swatch .cv-tooltip:before{
                                            background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                        }
                                    ';
									}

									//----swatches style------
									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) ) {
										$font_size = $rexvs_setup_data[ 'rexvs_swatches_font_size' ];
									}
									else {
										$font_size = 10;
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) ) {
										$swatches_bg_color = $rexvs_setup_data[ 'rexvs_swatches_bg_color' ];
									}
									else {
										$swatches_bg_color = '#dddddd';
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) ) {
										$swatches_color = $rexvs_setup_data[ 'rexvs_swatches_color' ];
									}
									else {
										$swatches_color = '#222222';
									}

									//--------hover background color---------
									$swatches_hvr_bg_color = '';
									$swatches_hvr_color    = '';

									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) ) {
										$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) ) {
										$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ];
									}

									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                        font-size: ' . $font_size . 'px;
                                        background-color: ' . $swatches_bg_color . ';
                                        color: ' . $swatches_color . ';
                                        border-color: ' . $swatches_bg_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:hover{
                                        background-color: ' . $swatches_hvr_bg_color . ';
                                        color: ' . $swatches_hvr_color . ';
                                        border-color: ' . $swatches_hvr_bg_color . ';
                                    }
                                ';


									//----selected swatches style------
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) ) {
										$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ];
									}
									else {
										$rexvs_seltd_swatches_bg_color = '#444';
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) ) {
										$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ];
									}
									else {
										$rexvs_seltd_swatches_color = '#fff';
									}

									$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch.selected {
                                        border-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:before{
                                        background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                        color: ' . $rexvs_seltd_swatches_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:after{
                                        border-color: ' . $rexvs_seltd_swatches_color . ';
                                    }
                                ';

									//----swatches border style when border in enabled------
									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_swatches_border' ] == 'on' ) {

										if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) ) {
											$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_swatches_border_size' ];
										}
										else {
											$rexvs_swatches_border_size = 1;
										}

										if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) ) {
											$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_swatches_border_style' ];
										}
										else {
											$rexvs_swatches_border_style = 'solid';
										}

										if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) ) {
											$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_swatches_border_color' ];
										}
										else {
											$rexvs_swatches_border_color = '#333';
										}

										//-----selected border-width and border-color---
										if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) ) {
											$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ];
										}
										if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) ) {
											$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ];
										}

										//-----hover border-width and border-color---
										if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) ) {
											$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ];
										}
										if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) ) {
											$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ];
										}

										$html .= '
                                        .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                            border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                        }
                                        .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:hover{
                                            border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                            border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                        }
                                        .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch.selected{
                                            border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                            border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                        }
                                    ';
									}


								}//--end rexvs_individual_attr_style condition--
							}//--end rexvs_disable_stylesheet condition--

							$html .= '</style>';
							//===dynamic Style for default dropdown===//
						}

					}
					return $html;
				}
			}

			return $html;
		}
		/**
		 * @desc if global attributes are available
		 */
		else {
			/**
			 * @desc child attributes for global product attributes,
			 * product is product, attribute is for global attribute
			 */
			$options   = $args[ 'options' ];  //child attr of all attr
			$product   = $args[ 'product' ];  //product details of all attr
			$attribute = $args[ 'attribute' ];//name of parent attr
			$class     = "variation-selector variation-select-{$attr->attribute_type}";
			$swatches  = '';

			if ( empty( $options ) && !empty( $product ) && !empty( $attribute ) ) {
				$attributes = $product->get_variation_attributes();
				$options    = $attributes[ $attribute ];
			}

			/**
			 * @desc Variation swatch for global attribute check real attr type with our swatch attr type
			 */
			if ( array_key_exists( $attr->attribute_type, $swatch_types ) ) {
				if ( !empty( $options ) && $product && taxonomy_exists( $attribute ) ) {
					// Get terms if this is a taxonomy - ordered. We need the names too.term contains child attributes and their descriptions
					$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
					foreach ( $terms as $term ) {
						if ( in_array( $term->slug, $options ) ) {
							$swatches .= apply_filters( 'rexvs_swatch_html', '', $term, $attr->attribute_type, $args );
						}
					}
				}
				if ( !empty( $swatches ) ) {
					$class    .= ' hidden';
					$swatches = '<div class="rexvs-swatches" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $swatches . '</div>';
					$html     = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $swatches;
					//===dynamic Style===//
					$html .= '<style>';
					if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {
						if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] != 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_tooltip' ] == 'on' ) {
								$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                    }
                                ';
							}

							//----swatches rounded/square style------
							if ( isset( $rexvs_setup_data[ 'rexvs_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_shape_style' ] == 'squared' ) {
								$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image img,
                                    .rexvs-variations .rexvs-swatches .swatch:before,
                                    .rexvs-variations .rexvs-swatches .swatch{
                                        border-radius: 0;
                                    }
                                ';
							}

							//----swatches style------
							if ( isset( $rexvs_setup_data[ 'rexvs_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shape_height' ] ) ) {
								$height = $rexvs_setup_data[ 'rexvs_shape_height' ];
							}
							else {
								$height = 40;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shape_width' ] ) ) {
								$width = $rexvs_setup_data[ 'rexvs_shape_width' ];
							}
							else {
								$width = 40;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) ) {
								$font_size = $rexvs_setup_data[ 'rexvs_swatches_font_size' ];
							}
							else {
								$font_size = 10;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) ) {
								$swatches_bg_color = $rexvs_setup_data[ 'rexvs_swatches_bg_color' ];
							}
							else {
								$swatches_bg_color = '#dddddd';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) ) {
								$swatches_color = $rexvs_setup_data[ 'rexvs_swatches_color' ];
							}
							else {
								$swatches_color = '#222222';
							}

							//--------hover background color---------
							$swatches_hvr_bg_color = '';
							$swatches_hvr_color    = '';

							if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) ) {
								$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) ) {
								$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ];
							}

							$html .= '
                                .rexvs-variations .rexvs-swatches .swatch{
                                    height: ' . $height . 'px;
                                    width: ' . $width . 'px;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                    background-color: transparent;
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:hover img {
                                    opacity: 0.4;
                                }
                            ';

							//----swatches border style------
							if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_swatches_border' ] == 'on' ) {

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) ) {
									$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_swatches_border_size' ];

								}
								else {
									$rexvs_swatches_border_size = 1;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) ) {
									$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_swatches_border_style' ];
								}
								else {
									$rexvs_swatches_border_style = 'solid';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) ) {
									$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_swatches_border_color' ];
								}
								else {
									$rexvs_swatches_border_color = '#333';
								}

								//-----selected border-width and border-color---
								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) ) {
									$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) ) {
									$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ];
								}

								//-----hover border-width and border-color---
								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) ) {
									$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) ) {
									$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ];
								}

								$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
							}

							//----selected swatches style------
							if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) ) {
								$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ];
							}
							else {
								$rexvs_seltd_swatches_bg_color = '#444';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) ) {
								$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ];
							}
							else {
								$rexvs_seltd_swatches_color = '#fff';
							}

							$html .= '
                                .rexvs-variations .rexvs-swatches .swatch:before {
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch:after {
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:before {
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                    opacity: 0.4;
                                }
                            ';

						}//--end  rexvs_individual_attr_style condition--


						//-----clear button style----
						$clr_btn_height   = '';
						$clr_btn_width    = '';
						$clr_btn_fnt_size = '';
						$clr_btn_radius   = '';
						$clr_btn_bg_color = '';
						$clr_btn_color    = '';

						if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_height' ] ) ) {
							$clr_btn_height = $rexvs_setup_data[ 'rexvs_clr_btn_height' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_width' ] ) ) {
							$clr_btn_width = $rexvs_setup_data[ 'rexvs_clr_btn_width' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ] ) ) {
							$clr_btn_fnt_size = $rexvs_setup_data[ 'rexvs_clr_btn_font_size' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_radius' ] ) ) {
							$clr_btn_radius = $rexvs_setup_data[ 'rexvs_clr_btn_radius' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ] ) ) {
							$clr_btn_bg_color = $rexvs_setup_data[ 'rexvs_clr_btn_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_clr_btn_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_clr_btn_color' ] ) ) {
							$clr_btn_color = $rexvs_setup_data[ 'rexvs_clr_btn_color' ];
						}
						$html .= '
                            ul.products .rexvs-variations .reset_variations,
                            .rexvs-variations .reset_variations {
                                width: ' . $clr_btn_width . 'px;
                                height: ' . $clr_btn_height . 'px;
                                line-height: ' . $clr_btn_height . 'px;
                                font-size: ' . $clr_btn_fnt_size . 'px!important;
                                background-color: ' . $clr_btn_bg_color . ';
                                color: ' . $clr_btn_color . ';
                                border-radius: ' . $clr_btn_radius . 'px;
                                
                            }
                            
                        ';

						//----shop page style-----
						if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

							$shop_swatch_hover_bg    = '';
							$shop_swatch_hover_color = '';
							$shop_swatch_seltd_bg    = '';
							$shop_swatch_seltd_color = '';

							if ( isset( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ] ) ) {
								$shop_swatch_hover_bg = $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_bg_color' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ] ) ) {
								$shop_swatch_hover_color = $rexvs_setup_data[ 'rexvs_shop_hvr_swatches_color' ];
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ] ) ) {
								$shop_swatch_seltd_bg = $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_bg_color' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ] ) ) {
								$shop_swatch_seltd_color = $rexvs_setup_data[ 'rexvs_shop_seltd_swatches_color' ];
							}

							$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch:hover{
                                    background-color: ' . $shop_swatch_hover_bg . ';
                                    color: ' . $shop_swatch_hover_color . ';
                                }
                                ul.products .rexvs-variations .rexvs-swatches .swatch:before {
                                    background-color: ' . $shop_swatch_seltd_bg . ';
                                    color: ' . $shop_swatch_seltd_color . ';
                                }
                                ul.products .rexvs-variations .rexvs-swatches .swatch:after {
                                    border-color: ' . $shop_swatch_seltd_color . ';
                                }
                            ';
						}

					}//--end rexvs_disable_stylesheet condition--

					$html .= '</style>';
					//===dynamic Style===//
				}
			}
			else {
				if ( isset( $rexvs_setup_data[ 'rexvs_default_dropdown_to_button' ] ) && $rexvs_setup_data[ 'rexvs_default_dropdown_to_button' ] == 'on' ) {
					if ( !empty( $options ) && $product && taxonomy_exists( $attribute ) ) {
						// Get terms if this is a taxonomy - ordered. We need the names too.
						$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

						foreach ( $terms as $term ) {
							if ( in_array( $term->slug, $options ) ) {
								$swatches .= apply_filters( 'rexvs_swatch_html', '', $term, 'default', $args );
							}
						}
					}

					if ( !empty( $swatches ) ) {
						$class    .= ' hidden';
						$swatches = '<div class="rexvs-swatches" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $swatches . '</div>';
						$html     = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $swatches;
						//===dynamic Style for default dropdown===//
						$html .= '<style>';
						if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

							if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] != 'on' ) {
								if ( isset( $rexvs_setup_data[ 'rexvs_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_tooltip' ] == 'on' ) {
									$html .= '
                                        .rexvs-variations .rexvs-swatches .swatch .cv-tooltip{
                                            display: block;
                                            font-size : ' . $rexvs_setup_data[ 'rexvs_tooltip_fnt_size' ] . 'px;
                                            color: ' . $rexvs_setup_data[ 'rexvs_tooltip_color' ] . ';
                                            background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                        }
                                        .rexvs-variations .rexvs-swatches .swatch .cv-tooltip:before{
                                            background-color: ' . $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] . ';
                                        }
                                    ';
								}

								//----swatches style------
								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) ) {
									$font_size = $rexvs_setup_data[ 'rexvs_swatches_font_size' ];
								}
								else {
									$font_size = 10;
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) ) {
									$swatches_bg_color = $rexvs_setup_data[ 'rexvs_swatches_bg_color' ];
								}
								else {
									$swatches_bg_color = '#dddddd';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) ) {
									$swatches_color = $rexvs_setup_data[ 'rexvs_swatches_color' ];
								}
								else {
									$swatches_color = '#222222';
								}

								//--------hover background color---------
								$swatches_hvr_bg_color = '';
								$swatches_hvr_color    = '';

								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) ) {
									$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ];
								}
								if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) ) {
									$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ];
								}

								$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                        font-size: ' . $font_size . 'px;
                                        background-color: ' . $swatches_bg_color . ';
                                        color: ' . $swatches_color . ';
                                        border-color: ' . $swatches_bg_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:hover{
                                        background-color: ' . $swatches_hvr_bg_color . ';
                                        color: ' . $swatches_hvr_color . ';
                                        border-color: ' . $swatches_hvr_bg_color . ';
                                    }
                                ';


								//----selected swatches style------
								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) ) {
									$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ];
								}
								else {
									$rexvs_seltd_swatches_bg_color = '#444';
								}

								if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) ) {
									$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ];
								}
								else {
									$rexvs_seltd_swatches_color = '#fff';
								}

								$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch.selected {
                                        border-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:before{
                                        background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                        color: ' . $rexvs_seltd_swatches_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:after{
                                        border-color: ' . $rexvs_seltd_swatches_color . ';
                                    }
                                ';

								//----swatches border style when border in enabled------
								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_swatches_border' ] == 'on' ) {

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) ) {
										$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_swatches_border_size' ];
									}
									else {
										$rexvs_swatches_border_size = 1;
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) ) {
										$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_swatches_border_style' ];
									}
									else {
										$rexvs_swatches_border_style = 'solid';
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) ) {
										$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_swatches_border_color' ];
									}
									else {
										$rexvs_swatches_border_color = '#333';
									}

									//-----selected border-width and border-color---
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) ) {
										$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) ) {
										$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ];
									}

									//-----hover border-width and border-color---
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) ) {
										$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ];
									}
									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) ) {
										$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ];
									}

									$html .= '
                                        .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                            border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                        }
                                        .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:hover{
                                            border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                            border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                        }
                                        .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch.selected{
                                            border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                            border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                        }
                                    ';
								}


							}//--end rexvs_individual_attr_style condition--
						}//--end rexvs_disable_stylesheet condition--

						$html .= '</style>';
						//===dynamic Style for default dropdown===//
					}
				}
			}

			return $html;

		}
	}

	/**
	 * @param $html
	 * @param $term
	 * @param $type
	 * @param $args
	 * @return mixed|string
	 *
	 * @desc prepares and returns swatches for global attributes.
	 */

	public function swatch_html( $html, $term, $type, $args )
	{
		$selected = sanitize_title( $args[ 'selected' ] ) == $term->slug ? 'selected' : '';

		$name = esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) );

		$rexvs_setup_data = unserialize( get_option( 'rexvs_setup_data' ) );

		if ( $term->description ) {
			$tooltip = $term->description;
		}
		else {
			$tooltip = $name;
		}

		switch ( $type ) {
			case 'color':
				$color = get_term_meta( $term->term_id, 'color', true );
				list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );
				$html = sprintf(
					'<span class="swatch swatch-color swatch-%s %s" style="background-color:%s; color:%s;" data-value="%s"><p class="cv-tooltip">%s</p></span>',
					esc_attr( $term->slug ),
					$selected,
					esc_attr( $color ),
					"rgba($r,$g,$b,0.5)",
					// esc_attr( $term->description ),
					esc_attr( $term->slug ),
					//$name,
					esc_attr( $tooltip )
				);
				//===dynamic Style===//
				$html .= '<style>';
				if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

					if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_color_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_color_tooltip' ] == 'on' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_color_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_color_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_color_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_color_tooltip_bg_color' ] . ';
                                    }
                                ';
						}

						//----swatches rounded/square style------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_color_shape_style' ] == 'color_squared' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color:before,
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color{
                                        border-radius: 0;
                                    }
                                ';
						}

						//----swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shape_height' ] ) ) {
							$height = $rexvs_setup_data[ 'rexvs_color_shape_height' ];
						}
						else {
							$height = 40;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shape_width' ] ) ) {
							$width = $rexvs_setup_data[ 'rexvs_color_shape_width' ];
						}
						else {
							$width = 40;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_font_size' ] ) ) {
							$font_size = $rexvs_setup_data[ 'rexvs_color_swatches_font_size' ];
						}
						else {
							$font_size = 10;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_bg_color' ] ) ) {
							$swatches_bg_color = $rexvs_setup_data[ 'rexvs_color_swatches_bg_color' ];
						}
						else {
							$swatches_bg_color = '#dddddd';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_color' ] ) ) {
							$swatches_color = $rexvs_setup_data[ 'rexvs_color_swatches_color' ];
						}
						else {
							$swatches_color = '#222222';
						}

						//--------hover style---------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_bg_color' ] ) ) {
							$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_color_hvr_swatches_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_color' ] ) ) {
							$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_color_hvr_swatches_color' ];
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-color{
                                    height: ' . $height . 'px;
                                    width: ' . $width . 'px;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-color:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                            ';

						//----swatches border style------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_color_swatches_border' ] == 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_border_size' ] ) ) {
								$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_color_swatches_border_size' ];
							}
							else {
								$rexvs_swatches_border_size = 1;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_border_style' ] ) ) {
								$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_color_swatches_border_style' ];
							}
							else {
								$rexvs_swatches_border_style = 'solid';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_border_color' ] ) ) {
								$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_color_swatches_border_color' ];
							}
							else {
								$rexvs_swatches_border_color = '#333';
							}

							//------hover style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_size' ] ) ) {
								$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_color' ] ) ) {
								$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_color' ];
							}

							//------selected style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_size' ] ) ) {
								$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_color' ] ) ) {
								$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_color' ];
							}

							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
						}

						//----selected swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_bg_color' ] ) ) {
							$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_color_seltd_swatches_bg_color' ];
						}
						else {
							$rexvs_seltd_swatches_bg_color = '#444';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_color' ] ) ) {
							$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_color_seltd_swatches_color' ];
						}
						else {
							$rexvs_seltd_swatches_color = '#fff';
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-color:before{
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-color:after{
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                            ';

					}//--end  rexvs_individual_attr_style condition--

					//----shop page style-----
					if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_color_shop_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shop_shape_height' ] ) ) {
							$shop_height = $rexvs_setup_data[ 'rexvs_color_shop_shape_height' ];
						}
						else {
							$shop_height = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_shop_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shop_shape_width' ] ) ) {
							$shop_width = $rexvs_setup_data[ 'rexvs_color_shop_shape_width' ];
						}
						else {
							$shop_width = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_shop_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shop_swatches_font_size' ] ) ) {
							$shop_font_size = $rexvs_setup_data[ 'rexvs_color_shop_swatches_font_size' ];
						}
						else {
							$shop_font_size = 11;
						}

						$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch.swatch-color{
                                    height: ' . $shop_height . 'px;
                                    width: ' . $shop_width . 'px;
                                    font-size: ' . $shop_font_size . 'px;
                                }
                            ';

					}//----shop page style-----

				}//--end rexvs_disable_stylesheet condition--

				$html .= '</style>';
				//===dynamic Style===//
				break;

			case 'image':
				$image = get_term_meta( $term->term_id, 'image', true );
				$image = $image ? wp_get_attachment_image_src( $image ) : '';
				$image = $image ? $image[ 0 ] : WC()->plugin_url() . '/assets/images/placeholder.png';
				$html  = sprintf(
					'<span class="swatch swatch-image swatch-%s %s" data-value="%s"><img src="%s" alt="%s"><p class="cv-tooltip">%s</p></span>',
					esc_attr( $term->slug ),
					$selected,
					// esc_attr( $term->description ),
					esc_attr( $term->slug ),
					esc_url( $image ),
					esc_attr( $name ),
					// esc_attr( $name ),
					esc_attr( $tooltip )
				);
				//===dynamic Style===//
				$html .= '<style>';
				if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

					if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_image_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_image_tooltip' ] == 'on' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_image_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_image_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_image_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_image_tooltip_bg_color' ] . ';
                                    }
                                ';
						}

						//----swatches rounded/square style------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_image_shape_style' ] == 'image_squared' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image:before,
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                        border-radius: 0;
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image img {
                                        border-radius: 0;
                                    }
                                ';
						}

						//----swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shape_height' ] ) ) {
							$height = $rexvs_setup_data[ 'rexvs_image_shape_height' ];
						}
						else {
							$height = 40;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shape_width' ] ) ) {
							$width = $rexvs_setup_data[ 'rexvs_image_shape_width' ];
						}
						else {
							$width = 40;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_font_size' ] ) ) {
							$font_size = $rexvs_setup_data[ 'rexvs_image_swatches_font_size' ];
						}
						else {
							$font_size = 10;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_bg_color' ] ) ) {
							$swatches_bg_color = $rexvs_setup_data[ 'rexvs_image_swatches_bg_color' ];
						}
						else {
							$swatches_bg_color = '#dddddd';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_color' ] ) ) {
							$swatches_color = $rexvs_setup_data[ 'rexvs_image_swatches_color' ];
						}
						else {
							$swatches_color = '#222222';
						}

						//--------hover style---------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_bg_color' ] ) ) {
							$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_image_hvr_swatches_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_color' ] ) ) {
							$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_image_hvr_swatches_color' ];
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                    height: ' . $height . 'px;
                                    width: ' . $width . 'px;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                            ';

						//----swatches border style------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_image_swatches_border' ] == 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_border_size' ] ) ) {
								$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_image_swatches_border_size' ];
							}
							else {
								$rexvs_swatches_border_size = 1;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_border_style' ] ) ) {
								$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_image_swatches_border_style' ];
							}
							else {
								$rexvs_swatches_border_style = 'solid';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_border_color' ] ) ) {
								$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_image_swatches_border_color' ];
							}
							else {
								$rexvs_swatches_border_color = '#333';
							}

							//------hover style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_size' ] ) ) {
								$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_color' ] ) ) {
								$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_color' ];
							}

							//------selected style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_size' ] ) ) {
								$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_color' ] ) ) {
								$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_color' ];
							}


							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
						}

						//----selected swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_bg_color' ] ) ) {
							$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_image_seltd_swatches_bg_color' ];
						}
						else {
							$rexvs_seltd_swatches_bg_color = '#444';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_color' ] ) ) {
							$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_image_seltd_swatches_color' ];
						}
						else {
							$rexvs_seltd_swatches_color = '#fff';
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:before{
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:after{
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                            ';

					}//--end  rexvs_individual_attr_style condition--

					//----shop page style-----
					if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_image_shop_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shop_shape_height' ] ) ) {
							$shop_height = $rexvs_setup_data[ 'rexvs_image_shop_shape_height' ];
						}
						else {
							$shop_height = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_shop_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shop_shape_width' ] ) ) {
							$shop_width = $rexvs_setup_data[ 'rexvs_image_shop_shape_width' ];
						}
						else {
							$shop_width = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_shop_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shop_swatches_font_size' ] ) ) {
							$shop_font_size = $rexvs_setup_data[ 'rexvs_image_shop_swatches_font_size' ];
						}
						else {
							$shop_font_size = 11;
						}

						$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                    height: ' . $shop_height . 'px;
                                    width: ' . $shop_width . 'px;
                                    font-size: ' . $shop_font_size . 'px;
                                }
                            ';

					}//----shop page style-----

				}//--end rexvs_disable_stylesheet condition--

				$html .= '</style>';
				//===dynamic Style===//
				break;

			case 'label':
				$label = get_term_meta( $term->term_id, 'label', true );
				$label = $label ? $label : $name;

				$html = sprintf(
					'<span class="swatch swatch-label swatch-%s %s" data-value="%s">%s<p class="cv-tooltip">%s</p></span>',
					esc_attr( $term->slug ),
					$selected,
					// esc_attr( $term->description ),
					esc_attr( $term->slug ),
					esc_html( $label ),
					esc_attr( $tooltip )
				);
				//===dynamic Style===//
				$html .= '<style>';
				if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

					if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_label_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_label_tooltip' ] == 'on' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_label_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_label_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_label_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_label_tooltip_bg_color' ] . ';
                                    }
                                ';
						}

						//----swatches rounded/square style------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_label_shape_style' ] == 'label_squared' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label:before,
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label{
                                        border-radius: 0;
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label img {
                                        border-radius: 0;
                                    }
                                ';
						}

						//----swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shape_height' ] ) ) {
							$height = $rexvs_setup_data[ 'rexvs_label_shape_height' ] . 'px';
						}
						else {
							$height = 'auto';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shape_width' ] ) ) {
							$width = $rexvs_setup_data[ 'rexvs_label_shape_width' ] . 'px';
						}
						else {
							$width = 'auto';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_font_size' ] ) ) {
							$font_size = $rexvs_setup_data[ 'rexvs_label_swatches_font_size' ];
						}
						else {
							$font_size = 10;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_bg_color' ] ) ) {
							$swatches_bg_color = $rexvs_setup_data[ 'rexvs_label_swatches_bg_color' ];
						}
						else {
							$swatches_bg_color = '#dddddd';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_color' ] ) ) {
							$swatches_color = $rexvs_setup_data[ 'rexvs_label_swatches_color' ];
						}
						else {
							$swatches_color = '#222222';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_top_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_top_padding' ] ) ) {
							$padding_top = $rexvs_setup_data[ 'rexvs_label_top_padding' ];
						}
						else {
							$padding_top = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_right_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_right_padding' ] ) ) {
							$padding_right = $rexvs_setup_data[ 'rexvs_label_right_padding' ];
						}
						else {
							$padding_right = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_bottom_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_bottom_padding' ] ) ) {
							$padding_bottom = $rexvs_setup_data[ 'rexvs_label_bottom_padding' ];
						}
						else {
							$padding_bottom = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_left_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_left_padding' ] ) ) {
							$padding_left = $rexvs_setup_data[ 'rexvs_label_left_padding' ];
						}
						else {
							$padding_left = 0;
						}

						//--------hover style---------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_bg_color' ] ) ) {
							$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_label_hvr_swatches_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_color' ] ) ) {
							$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_label_hvr_swatches_color' ];
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-label{
                                    height: ' . $height . ';
                                    width: ' . $width . ';
                                    min-height: 40px;
                                    min-width: 40px;
                                    line-height: normal;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                    padding: ' . $padding_top . 'px ' . $padding_right . 'px ' . $padding_bottom . 'px ' . $padding_left . 'px;
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-label:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                            ';

						//----swatches border style------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_label_swatches_border' ] == 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_border_size' ] ) ) {
								$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_label_swatches_border_size' ];
							}
							else {
								$rexvs_swatches_border_size = 1;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_border_style' ] ) ) {
								$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_label_swatches_border_style' ];
							}
							else {
								$rexvs_swatches_border_style = 'solid';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_border_color' ] ) ) {
								$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_label_swatches_border_color' ];
							}
							else {
								$rexvs_swatches_border_color = '#333';
							}

							//------hover style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_size' ] ) ) {
								$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_color' ] ) ) {
								$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_color' ];
							}

							//------selected style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_size' ] ) ) {
								$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_color' ] ) ) {
								$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_color' ];
							}

							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
						}

						//----selected swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_bg_color' ] ) ) {
							$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_label_seltd_swatches_bg_color' ];
						}
						else {
							$rexvs_seltd_swatches_bg_color = '#444';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_color' ] ) ) {
							$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_label_seltd_swatches_color' ];
						}
						else {
							$rexvs_seltd_swatches_color = '#fff';
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-label:before{
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-label:after{
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                            ';

					}//--end  rexvs_individual_attr_style condition--

					//----shop page style-----
					if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_label_shop_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shop_shape_height' ] ) ) {
							$shop_height = $rexvs_setup_data[ 'rexvs_label_shop_shape_height' ];
						}
						else {
							$shop_height = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_shop_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shop_shape_width' ] ) ) {
							$shop_width = $rexvs_setup_data[ 'rexvs_label_shop_shape_width' ];
						}
						else {
							$shop_width = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_shop_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shop_swatches_font_size' ] ) ) {
							$shop_font_size = $rexvs_setup_data[ 'rexvs_label_shop_swatches_font_size' ];
						}
						else {
							$shop_font_size = 11;
						}

						$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch.swatch-label{
                                    min-height: ' . $shop_height . 'px;
                                    min-width: ' . $shop_width . 'px;
                                    font-size: ' . $shop_font_size . 'px;
                                }
                            ';

					}//----shop page style-----

				}//--end rexvs_disable_stylesheet condition--

				$html .= '</style>';
				//===dynamic Style===//
				break;

			case 'default':
				// $name = substr($name, 0, 2);
				$html = sprintf(
					'<span class="swatch rex-default-swatch swatch-%s %s" data-value="%s">%s<p class="cv-tooltip">%s</p></span>',
					esc_attr( $term->slug ),
					$selected,
					// esc_attr( $term->description ),
					esc_attr( $term->slug ),
					esc_html( $name ),
					esc_attr( $tooltip )
				);
				//===dynamic Style===//
				$html .= '<style>';
				if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

					if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_select_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_select_tooltip' ] == 'on' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_select_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_select_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_select_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_select_tooltip_bg_color' ] . ';
                                    }
                                ';
						}

						//----swatches rounded/square style------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_select_shape_style' ] == 'label_squared' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:before,
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                        border-radius: 0;
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch img {
                                        border-radius: 0;
                                    }
                                ';
						}

						//----swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shape_height' ] ) ) {
							$height = $rexvs_setup_data[ 'rexvs_select_shape_height' ] . 'px';
						}
						else {
							$height = 'auto';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shape_width' ] ) ) {
							$width = $rexvs_setup_data[ 'rexvs_select_shape_width' ] . 'px';
						}
						else {
							$width = 'auto';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_font_size' ] ) ) {
							$font_size = $rexvs_setup_data[ 'rexvs_select_swatches_font_size' ];
						}
						else {
							$font_size = 10;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_bg_color' ] ) ) {
							$swatches_bg_color = $rexvs_setup_data[ 'rexvs_select_swatches_bg_color' ];
						}
						else {
							$swatches_bg_color = '#dddddd';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_color' ] ) ) {
							$swatches_color = $rexvs_setup_data[ 'rexvs_select_swatches_color' ];
						}
						else {
							$swatches_color = '#222222';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_top_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_top_padding' ] ) ) {
							$padding_top = $rexvs_setup_data[ 'rexvs_select_top_padding' ];
						}
						else {
							$padding_top = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_right_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_right_padding' ] ) ) {
							$padding_right = $rexvs_setup_data[ 'rexvs_select_right_padding' ];
						}
						else {
							$padding_right = 12;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_bottom_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_bottom_padding' ] ) ) {
							$padding_bottom = $rexvs_setup_data[ 'rexvs_select_bottom_padding' ];
						}
						else {
							$padding_bottom = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_left_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_left_padding' ] ) ) {
							$padding_left = $rexvs_setup_data[ 'rexvs_select_left_padding' ];
						}
						else {
							$padding_left = 12;
						}

						//--------hover style---------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_bg_color' ] ) ) {
							$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_select_hvr_swatches_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_color' ] ) ) {
							$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_select_hvr_swatches_color' ];
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                    height: ' . $height . ';
                                    width: ' . $width . ';
                                    min-height: 30px;
                                    min-width: 30px;
                                    line-height: normal;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                    padding: ' . $padding_top . 'px ' . $padding_right . 'px ' . $padding_bottom . 'px ' . $padding_left . 'px;
                                }
                                .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                            ';

						//----swatches border style------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_select_swatches_border' ] == 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_border_size' ] ) ) {
								$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_select_swatches_border_size' ];
							}
							else {
								$rexvs_swatches_border_size = 1;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_border_style' ] ) ) {
								$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_select_swatches_border_style' ];
							}
							else {
								$rexvs_swatches_border_style = 'solid';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_border_color' ] ) ) {
								$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_select_swatches_border_color' ];
							}
							else {
								$rexvs_swatches_border_color = '#333';
							}

							//------hover style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_size' ] ) ) {
								$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_color' ] ) ) {
								$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_color' ];
							}

							//------selected style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_size' ] ) ) {
								$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_color' ] ) ) {
								$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_color' ];
							}

							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
						}

						//----selected swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_bg_color' ] ) ) {
							$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_select_seltd_swatches_bg_color' ];
						}
						else {
							$rexvs_seltd_swatches_bg_color = '#444';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_color' ] ) ) {
							$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_select_seltd_swatches_color' ];
						}
						else {
							$rexvs_seltd_swatches_color = '#fff';
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:before{
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:after{
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                            ';

					}//--end  rexvs_individual_attr_style condition--

				}//--end rexvs_disable_stylesheet condition--

				//----shop page style-----
				if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_top_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_top_padding' ] ) ) {
						$shop_pt = $rexvs_setup_data[ 'rexvs_select_shop_top_padding' ];
					}
					else {
						$shop_pt = 0;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_right_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_right_padding' ] ) ) {
						$shop_pr = $rexvs_setup_data[ 'rexvs_select_shop_right_padding' ];
					}
					else {
						$shop_pr = 12;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_bottom_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_bottom_padding' ] ) ) {
						$shop_pb = $rexvs_setup_data[ 'rexvs_select_shop_bottom_padding' ];
					}
					else {
						$shop_pb = 0;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_left_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_left_padding' ] ) ) {
						$shop_pl = $rexvs_setup_data[ 'rexvs_select_shop_left_padding' ];
					}
					else {
						$shop_pl = 12;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_radius' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_radius' ] ) ) {
						$shop_radius = $rexvs_setup_data[ 'rexvs_select_shop_radius' ];
					}
					else {
						$shop_radius = 3;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_swatches_font_size' ] ) ) {
						$shop_font_size = $rexvs_setup_data[ 'rexvs_select_shop_swatches_font_size' ];
					}
					else {
						$shop_font_size = 13;
					}

					$html .= '
                            ul.products .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch {
                                font-size: ' . $shop_font_size . 'px;
                                padding: ' . $shop_pt . 'px ' . $shop_pr . 'px ' . $shop_pb . 'px ' . $shop_pl . 'px;
                                border-radius: ' . $shop_radius . 'px;
                            }
                            ul.products .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:before {
                                border-radius: ' . $shop_radius . 'px;
                            }
                        ';

				}//----shop page style-----

				$html .= '</style>';
				//===dynamic Style===//
				break;

		}
		return $html;
	}

	public function custom_swatch_html( $html, $term, $type, $args )
	{
//		if ($this->first_run) {
//			$this->rexvs_get_product_variations();
//			$this->first_run = false;
//		}

		$selected         = sanitize_title( $args[ 'selected' ] ) == sanitize_title( $term[ 'slug' ] ) ? 'selected' : '';
		$name             = esc_html( apply_filters( 'woocommerce_variation_option_name', $term[ 'slug' ] ) );
		$rexvs_setup_data = unserialize( get_option( 'rexvs_setup_data' ) );//get data for swatches
		$tooltip          = $name;                                          //name of tooltip

		switch ( $type ) {
			case 'color':
				$color = $term[ 'color_id' ];
				list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );
				$html = sprintf(
					'<span class="swatch swatch-color swatch-%s %s variable-item" style="background-color:%s; color:%s;" data-value="%s"><p class="cv-tooltip">%s</p></span>',
					esc_attr( $term[ 'slug' ] ),
					$selected,
					esc_attr( $color ),
					"rgba($r,$g,$b,0.5)",
					// esc_attr( $term->description ),
					esc_attr( $term[ 'slug' ] ),
					//$name,
					esc_attr( $tooltip )
				);
				//===dynamic Style===//
				$html .= '<style>';
				if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

					if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_color_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_color_tooltip' ] == 'on' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_color_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_color_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_color_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_color_tooltip_bg_color' ] . ';
                                    }
                                ';
						}

						//----swatches rounded/square style------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_color_shape_style' ] == 'color_squared' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color:before,
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color{
                                        border-radius: 0;
                                    }
                                ';
						}

						//----swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shape_height' ] ) ) {
							$height = $rexvs_setup_data[ 'rexvs_color_shape_height' ];
						}
						else {
							$height = 40;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shape_width' ] ) ) {
							$width = $rexvs_setup_data[ 'rexvs_color_shape_width' ];
						}
						else {
							$width = 40;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_font_size' ] ) ) {
							$font_size = $rexvs_setup_data[ 'rexvs_color_swatches_font_size' ];
						}
						else {
							$font_size = 10;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_bg_color' ] ) ) {
							$swatches_bg_color = $rexvs_setup_data[ 'rexvs_color_swatches_bg_color' ];
						}
						else {
							$swatches_bg_color = '#dddddd';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_color' ] ) ) {
							$swatches_color = $rexvs_setup_data[ 'rexvs_color_swatches_color' ];
						}
						else {
							$swatches_color = '#222222';
						}

						//--------hover style---------
						$swatches_hvr_color    = '';
						$swatches_hvr_bg_color = '';

						if ( isset( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_bg_color' ] ) ) {
							$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_color_hvr_swatches_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_color' ] ) ) {
							$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_color_hvr_swatches_color' ];
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-color{
                                    height: ' . $height . 'px;
                                    width: ' . $width . 'px;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-color:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                            ';

						//----swatches border style------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_color_swatches_border' ] == 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_border_size' ] ) ) {
								$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_color_swatches_border_size' ];
							}
							else {
								$rexvs_swatches_border_size = 1;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_border_style' ] ) ) {
								$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_color_swatches_border_style' ];
							}
							else {
								$rexvs_swatches_border_style = 'solid';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_color_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_swatches_border_color' ] ) ) {
								$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_color_swatches_border_color' ];
							}
							else {
								$rexvs_swatches_border_color = '#333';
							}

							//------hover style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_size' ] ) ) {
								$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_color' ] ) ) {
								$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_color_hvr_swatches_border_color' ];
							}

							//------selected style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_size' ] ) ) {
								$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_color' ] ) ) {
								$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_color_seltd_swatches_border_color' ];
							}

							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-color.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
						}

						//----selected swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_bg_color' ] ) ) {
							$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_color_seltd_swatches_bg_color' ];
						}
						else {
							$rexvs_seltd_swatches_bg_color = '#444';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_seltd_swatches_color' ] ) ) {
							$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_color_seltd_swatches_color' ];
						}
						else {
							$rexvs_seltd_swatches_color = '#fff';
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-color:before{
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-color:after{
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                            ';

					}//--end  rexvs_individual_attr_style condition--

					//----shop page style-----
					if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_color_shop_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shop_shape_height' ] ) ) {
							$shop_height = $rexvs_setup_data[ 'rexvs_color_shop_shape_height' ];
						}
						else {
							$shop_height = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_shop_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shop_shape_width' ] ) ) {
							$shop_width = $rexvs_setup_data[ 'rexvs_color_shop_shape_width' ];
						}
						else {
							$shop_width = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_color_shop_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_color_shop_swatches_font_size' ] ) ) {
							$shop_font_size = $rexvs_setup_data[ 'rexvs_color_shop_swatches_font_size' ];
						}
						else {
							$shop_font_size = 11;
						}

						$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch.swatch-color{
                                    height: ' . $shop_height . 'px;
                                    width: ' . $shop_width . 'px;
                                    font-size: ' . $shop_font_size . 'px;
                                }
                            ';

					}//----shop page style-----

				}//--end rexvs_disable_stylesheet condition--

				$html .= '</style>';
				//===dynamic Style===//
				break;
			case 'image':
				$image = $term[ 'image_url' ];
				$image = $image ? $image : WC()->plugin_url() . '/assets/images/placeholder.png';
				$html  = sprintf(
					'<span class="swatch swatch-image swatch-%s %s" data-value="%s"><img src="%s" alt="%s" style="margin-bottom: unset"><p class="cv-tooltip">%s</p></span>',
					esc_attr( $term[ 'slug' ] ),
					$selected,
					esc_attr( $term[ 'slug' ] ),
					esc_url( $image ),
					esc_attr( $name ),
					esc_attr( $tooltip )
				);
				//===dynamic Style===//
				$html .= '<style>';
				if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

					if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_image_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_image_tooltip' ] == 'on' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_image_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_image_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_image_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_image_tooltip_bg_color' ] . ';
                                    }
                                ';
						}

						//----swatches rounded/square style------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_image_shape_style' ] == 'image_squared' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image:before,
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                        border-radius: 0;
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image img {
                                        border-radius: 0;
                                    }
                                ';
						}

						//----swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shape_height' ] ) ) {
							$height = $rexvs_setup_data[ 'rexvs_image_shape_height' ];
						}
						else {
							$height = 40;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shape_width' ] ) ) {
							$width = $rexvs_setup_data[ 'rexvs_image_shape_width' ];
						}
						else {
							$width = 40;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_font_size' ] ) ) {
							$font_size = $rexvs_setup_data[ 'rexvs_image_swatches_font_size' ];
						}
						else {
							$font_size = 10;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_bg_color' ] ) ) {
							$swatches_bg_color = $rexvs_setup_data[ 'rexvs_image_swatches_bg_color' ];
						}
						else {
							$swatches_bg_color = '#dddddd';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_color' ] ) ) {
							$swatches_color = $rexvs_setup_data[ 'rexvs_image_swatches_color' ];
						}
						else {
							$swatches_color = '#222222';
						}

						//--------hover style---------
						$swatches_hvr_bg_color = '';
						$swatches_hvr_color    = '';
						if ( isset( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_bg_color' ] ) ) {
							$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_image_hvr_swatches_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_color' ] ) ) {
							$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_image_hvr_swatches_color' ];
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                    height: ' . $height . 'px;
                                    width: ' . $width . 'px;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                            ';

						//----swatches border style------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_image_swatches_border' ] == 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_border_size' ] ) ) {
								$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_image_swatches_border_size' ];
							}
							else {
								$rexvs_swatches_border_size = 1;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_border_style' ] ) ) {
								$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_image_swatches_border_style' ];
							}
							else {
								$rexvs_swatches_border_style = 'solid';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_image_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_swatches_border_color' ] ) ) {
								$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_image_swatches_border_color' ];
							}
							else {
								$rexvs_swatches_border_color = '#333';
							}

							//------hover style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_size' ] ) ) {
								$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_color' ] ) ) {
								$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_image_hvr_swatches_border_color' ];
							}

							//------selected style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_size' ] ) ) {
								$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_color' ] ) ) {
								$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_image_seltd_swatches_border_color' ];
							}


							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-image.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
						}

						//----selected swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_bg_color' ] ) ) {
							$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_image_seltd_swatches_bg_color' ];
						}
						else {
							$rexvs_seltd_swatches_bg_color = '#444';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_seltd_swatches_color' ] ) ) {
							$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_image_seltd_swatches_color' ];
						}
						else {
							$rexvs_seltd_swatches_color = '#fff';
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:before{
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-image:after{
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                            ';

					}//--end  rexvs_individual_attr_style condition--

					//----shop page style-----
					if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_image_shop_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shop_shape_height' ] ) ) {
							$shop_height = $rexvs_setup_data[ 'rexvs_image_shop_shape_height' ];
						}
						else {
							$shop_height = 30;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_shop_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shop_shape_width' ] ) ) {
							$shop_width = $rexvs_setup_data[ 'rexvs_image_shop_shape_width' ];
						}
						else {
							$shop_width = 30;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_image_shop_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_image_shop_swatches_font_size' ] ) ) {
							$shop_font_size = $rexvs_setup_data[ 'rexvs_image_shop_swatches_font_size' ];
						}
						else {
							$shop_font_size = 11;
						}

						$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch.swatch-image{
                                    height: ' . $shop_height . 'px;
                                    width: ' . $shop_width . 'px;
                                    font-size: ' . $shop_font_size . 'px;
                                }
                            ';

					}//----shop page style-----

				}//--end rexvs_disable_stylesheet condition--

				$html .= '</style>';
				//===dynamic Style===//
				break;
			case 'label':
				$label = $term[ 'label' ];
				$html  = sprintf(
					'<span class="swatch swatch-label swatch-%s %s" data-value="%s">%s<p class="cv-tooltip">%s</p></span>',
					esc_attr( $term[ 'slug' ] ),
					$selected,
					esc_attr( $term[ 'slug' ] ),
					esc_html( $label ),
					esc_attr( $tooltip )
				);
				//===dynamic Style===//
				$html .= '<style>';
				if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

					if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_label_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_label_tooltip' ] == 'on' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_label_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_label_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_label_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_label_tooltip_bg_color' ] . ';
                                    }
                                ';
						}

						//----swatches rounded/square style------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_label_shape_style' ] == 'label_squared' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label:before,
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label{
                                        border-radius: 0;
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label img {
                                        border-radius: 0;
                                    }
                                ';
						}

						//----swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shape_height' ] ) ) {
							$height = $rexvs_setup_data[ 'rexvs_label_shape_height' ] . 'px';
						}
						else {
							$height = 'auto';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shape_width' ] ) ) {
							$width = $rexvs_setup_data[ 'rexvs_label_shape_width' ] . 'px';
						}
						else {
							$width = 'auto';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_font_size' ] ) ) {
							$font_size = $rexvs_setup_data[ 'rexvs_label_swatches_font_size' ];
						}
						else {
							$font_size = 10;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_bg_color' ] ) ) {
							$swatches_bg_color = $rexvs_setup_data[ 'rexvs_label_swatches_bg_color' ];
						}
						else {
							$swatches_bg_color = '#dddddd';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_color' ] ) ) {
							$swatches_color = $rexvs_setup_data[ 'rexvs_label_swatches_color' ];
						}
						else {
							$swatches_color = '#222222';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_top_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_top_padding' ] ) ) {
							$padding_top = $rexvs_setup_data[ 'rexvs_label_top_padding' ];
						}
						else {
							$padding_top = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_right_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_right_padding' ] ) ) {
							$padding_right = $rexvs_setup_data[ 'rexvs_label_right_padding' ];
						}
						else {
							$padding_right = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_bottom_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_bottom_padding' ] ) ) {
							$padding_bottom = $rexvs_setup_data[ 'rexvs_label_bottom_padding' ];
						}
						else {
							$padding_bottom = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_left_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_left_padding' ] ) ) {
							$padding_left = $rexvs_setup_data[ 'rexvs_label_left_padding' ];
						}
						else {
							$padding_left = 0;
						}

						//--------hover style---------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_bg_color' ] ) ) {
							$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_label_hvr_swatches_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_color' ] ) ) {
							$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_label_hvr_swatches_color' ];
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-label{
                                    height: ' . $height . ';
                                    width: ' . $width . ';
                                    min-height: 40px;
                                    min-width: 40px;
                                    line-height: normal;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                    padding: ' . $padding_top . 'px ' . $padding_right . 'px ' . $padding_bottom . 'px ' . $padding_left . 'px;
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-label:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                            ';

						//----swatches border style------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_label_swatches_border' ] == 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_border_size' ] ) ) {
								$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_label_swatches_border_size' ];
							}
							else {
								$rexvs_swatches_border_size = 1;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_border_style' ] ) ) {
								$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_label_swatches_border_style' ];
							}
							else {
								$rexvs_swatches_border_style = 'solid';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_label_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_swatches_border_color' ] ) ) {
								$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_label_swatches_border_color' ];
							}
							else {
								$rexvs_swatches_border_color = '#333';
							}

							//------hover style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_size' ] ) ) {
								$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_color' ] ) ) {
								$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_label_hvr_swatches_border_color' ];
							}

							//------selected style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_size' ] ) ) {
								$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_color' ] ) ) {
								$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_label_seltd_swatches_border_color' ];
							}

							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.swatch-label.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
						}

						//----selected swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_bg_color' ] ) ) {
							$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_label_seltd_swatches_bg_color' ];
						}
						else {
							$rexvs_seltd_swatches_bg_color = '#444';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_seltd_swatches_color' ] ) ) {
							$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_label_seltd_swatches_color' ];
						}
						else {
							$rexvs_seltd_swatches_color = '#fff';
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.swatch-label:before{
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.swatch-label:after{
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                            ';

					}//--end  rexvs_individual_attr_style condition--

					//----shop page style-----
					if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_label_shop_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shop_shape_height' ] ) ) {
							$shop_height = $rexvs_setup_data[ 'rexvs_label_shop_shape_height' ];
						}
						else {
							$shop_height = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_shop_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shop_shape_width' ] ) ) {
							$shop_width = $rexvs_setup_data[ 'rexvs_label_shop_shape_width' ];
						}
						else {
							$shop_width = 28;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_label_shop_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_label_shop_swatches_font_size' ] ) ) {
							$shop_font_size = $rexvs_setup_data[ 'rexvs_label_shop_swatches_font_size' ];
						}
						else {
							$shop_font_size = 11;
						}

						$html .= '
                                ul.products .rexvs-variations .rexvs-swatches .swatch.swatch-label{
                                    min-height: ' . $shop_height . 'px;
                                    min-width: ' . $shop_width . 'px;
                                    font-size: ' . $shop_font_size . 'px;
                                }
                            ';

					}//----shop page style-----

				}//--end rexvs_disable_stylesheet condition--

				$html .= '</style>';
				//===dynamic Style===//
				break;
			case 'default':
				$html = sprintf(
					'<span class="swatch rex-default-swatch swatch-%s %s" data-value="%s">%s<p class="cv-tooltip">%s</p></span>',
					esc_attr( $term[ 'slug' ] ),
					$selected,
					esc_attr( $term[ 'slug' ] ),
					esc_html( $name ),
					esc_attr( $tooltip )
				);
				//===dynamic Style===//
				$html .= '<style>';
				if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] != 'on' ) {

					if ( $rexvs_setup_data[ 'rexvs_individual_attr_style' ] == 'on' ) {

						if ( isset( $rexvs_setup_data[ 'rexvs_select_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_select_tooltip' ] == 'on' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch .cv-tooltip{
                                        display: block;
                                        font-size : ' . $rexvs_setup_data[ 'rexvs_select_tooltip_fnt_size' ] . 'px;
                                        color: ' . $rexvs_setup_data[ 'rexvs_select_tooltip_color' ] . ';
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_select_tooltip_bg_color' ] . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch .cv-tooltip:before{
                                        background-color: ' . $rexvs_setup_data[ 'rexvs_select_tooltip_bg_color' ] . ';
                                    }
                                ';
						}

						//----swatches rounded/square style------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_select_shape_style' ] == 'label_squared' ) {
							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:before,
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                        border-radius: 0;
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch img {
                                        border-radius: 0;
                                    }
                                ';
						}

						//----swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_shape_height' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shape_height' ] ) ) {
							$height = $rexvs_setup_data[ 'rexvs_select_shape_height' ] . 'px';
						}
						else {
							$height = 'auto';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_shape_width' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shape_width' ] ) ) {
							$width = $rexvs_setup_data[ 'rexvs_select_shape_width' ] . 'px';
						}
						else {
							$width = 'auto';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_font_size' ] ) ) {
							$font_size = $rexvs_setup_data[ 'rexvs_select_swatches_font_size' ];
						}
						else {
							$font_size = 10;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_bg_color' ] ) ) {
							$swatches_bg_color = $rexvs_setup_data[ 'rexvs_select_swatches_bg_color' ];
						}
						else {
							$swatches_bg_color = '#dddddd';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_color' ] ) ) {
							$swatches_color = $rexvs_setup_data[ 'rexvs_select_swatches_color' ];
						}
						else {
							$swatches_color = '#222222';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_top_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_top_padding' ] ) ) {
							$padding_top = $rexvs_setup_data[ 'rexvs_select_top_padding' ];
						}
						else {
							$padding_top = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_right_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_right_padding' ] ) ) {
							$padding_right = $rexvs_setup_data[ 'rexvs_select_right_padding' ];
						}
						else {
							$padding_right = 12;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_bottom_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_bottom_padding' ] ) ) {
							$padding_bottom = $rexvs_setup_data[ 'rexvs_select_bottom_padding' ];
						}
						else {
							$padding_bottom = 0;
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_left_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_left_padding' ] ) ) {
							$padding_left = $rexvs_setup_data[ 'rexvs_select_left_padding' ];
						}
						else {
							$padding_left = 12;
						}

						//--------hover style---------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_bg_color' ] ) ) {
							$swatches_hvr_bg_color = $rexvs_setup_data[ 'rexvs_select_hvr_swatches_bg_color' ];
						}
						if ( isset( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_color' ] ) ) {
							$swatches_hvr_color = $rexvs_setup_data[ 'rexvs_select_hvr_swatches_color' ];
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                    height: ' . $height . ';
                                    width: ' . $width . ';
                                    min-height: 30px;
                                    min-width: 30px;
                                    line-height: normal;
                                    font-size: ' . $font_size . 'px;
                                    background-color: ' . $swatches_bg_color . ';
                                    color: ' . $swatches_color . ';
                                    padding: ' . $padding_top . 'px ' . $padding_right . 'px ' . $padding_bottom . 'px ' . $padding_left . 'px;
                                }
                                .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:hover{
                                    background-color: ' . $swatches_hvr_bg_color . ';
                                    color: ' . $swatches_hvr_color . ';
                                }
                            ';

						//----swatches border style------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_select_swatches_border' ] == 'on' ) {

							if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_border_size' ] ) ) {
								$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_select_swatches_border_size' ];
							}
							else {
								$rexvs_swatches_border_size = 1;
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_border_style' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_border_style' ] ) ) {
								$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_select_swatches_border_style' ];
							}
							else {
								$rexvs_swatches_border_style = 'solid';
							}

							if ( isset( $rexvs_setup_data[ 'rexvs_select_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_swatches_border_color' ] ) ) {
								$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_select_swatches_border_color' ];
							}
							else {
								$rexvs_swatches_border_color = '#333';
							}

							//------hover style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_size' ] ) ) {
								$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_color' ] ) ) {
								$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_select_hvr_swatches_border_color' ];
							}

							//------selected style-------
							if ( isset( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_size' ] ) ) {
								$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_size' ];
							}
							if ( isset( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_color' ] ) ) {
								$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_select_seltd_swatches_border_color' ];
							}

							$html .= '
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch{
                                        border: ' . $rexvs_swatches_border_size . 'px ' . $rexvs_swatches_border_style . ' ' . $rexvs_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:hover{
                                        border-width: ' . $rexvs_hvr_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_hvr_swatches_border_color . ';
                                    }
                                    .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch.selected{
                                        border-width: ' . $rexvs_seltd_swatches_border_size . 'px;
                                        border-color: ' . $rexvs_seltd_swatches_border_color . ';
                                    }
                                ';
						}

						//----selected swatches style------
						if ( isset( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_bg_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_bg_color' ] ) ) {
							$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_select_seltd_swatches_bg_color' ];
						}
						else {
							$rexvs_seltd_swatches_bg_color = '#444';
						}

						if ( isset( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_color' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_seltd_swatches_color' ] ) ) {
							$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_select_seltd_swatches_color' ];
						}
						else {
							$rexvs_seltd_swatches_color = '#fff';
						}

						$html .= '
                                .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:before{
                                    background-color: ' . $rexvs_seltd_swatches_bg_color . ';
                                    color: ' . $rexvs_seltd_swatches_color . ';
                                }
                                .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:after{
                                    border-color: ' . $rexvs_seltd_swatches_color . ';
                                }
                            ';

					}//--end  rexvs_individual_attr_style condition--

				}//--end rexvs_disable_stylesheet condition--

				//----shop page style-----
				if ( $rexvs_setup_data[ 'rexvs_shop_swatch' ] == 'on' ) {

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_top_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_top_padding' ] ) ) {
						$shop_pt = $rexvs_setup_data[ 'rexvs_select_shop_top_padding' ];
					}
					else {
						$shop_pt = 0;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_right_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_right_padding' ] ) ) {
						$shop_pr = $rexvs_setup_data[ 'rexvs_select_shop_right_padding' ];
					}
					else {
						$shop_pr = 12;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_bottom_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_bottom_padding' ] ) ) {
						$shop_pb = $rexvs_setup_data[ 'rexvs_select_shop_bottom_padding' ];
					}
					else {
						$shop_pb = 0;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_left_padding' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_left_padding' ] ) ) {
						$shop_pl = $rexvs_setup_data[ 'rexvs_select_shop_left_padding' ];
					}
					else {
						$shop_pl = 12;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_radius' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_radius' ] ) ) {
						$shop_radius = $rexvs_setup_data[ 'rexvs_select_shop_radius' ];
					}
					else {
						$shop_radius = 3;
					}

					if ( isset( $rexvs_setup_data[ 'rexvs_select_shop_swatches_font_size' ] ) && !empty( $rexvs_setup_data[ 'rexvs_select_shop_swatches_font_size' ] ) ) {
						$shop_font_size = $rexvs_setup_data[ 'rexvs_select_shop_swatches_font_size' ];
					}
					else {
						$shop_font_size = 13;
					}

					$html .= '
                            ul.products .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch {
                                font-size: ' . $shop_font_size . 'px;
                                padding: ' . $shop_pt . 'px ' . $shop_pr . 'px ' . $shop_pb . 'px ' . $shop_pl . 'px;
                                border-radius: ' . $shop_radius . 'px;
                            }
                            ul.products .rexvs-variations .rexvs-swatches .swatch.rex-default-swatch:before {
                                border-radius: ' . $shop_radius . 'px;
                            }
                        ';

				}//----shop page style-----

				$html .= '</style>';
				//===dynamic Style===//
				break;

		}

		return $html;
	}
}
<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/admin
 */
class REX_REX_Variation_Swatches_For_Woocommerce_Admin_Ajax {
	/**
	 * General settings
	 */
	public function rexvs_settings_submit()
	{
		$rexvs_default_dropdown_to_button         = sanitize_text_field( $_POST[ 'rexvs_default_dropdown_to_button' ] );
		$rexvs_delete_data                        = sanitize_text_field( $_POST[ 'rexvs_delete_data' ] );
		$rexvs_disable_stylesheet                 = sanitize_text_field( $_POST[ 'rexvs_disable_stylesheet' ] );
		$rexvs_enable_customAttribute             = sanitize_text_field( $_POST[ 'rexvs_enable_customAttribute' ] );
		$rexvs_individual_attr_style              = sanitize_text_field( $_POST[ 'rexvs_individual_attr_style' ] );
		$rexvs_shop_swatch                        = sanitize_text_field( $_POST[ 'rexvs_shop_swatch' ] );
		$rexvs_tooltip                            = sanitize_text_field( $_POST[ 'rexvs_tooltip' ] );
		$rexvs_shape_style                        = sanitize_text_field( $_POST[ 'rexvs_shape_style' ] );
		$rexvs_shape_height                       = sanitize_text_field( $_POST[ 'rexvs_shape_height' ] );
		$rexvs_shape_width                        = sanitize_text_field( $_POST[ 'rexvs_shape_width' ] );
		$rexvs_tooltip_fnt_size                   = sanitize_text_field( $_POST[ 'rexvs_tooltip_fnt_size' ] );
		$rexvs_tooltip_color                      = sanitize_text_field( $_POST[ 'rexvs_tooltip_color' ] );
		$rexvs_tooltip_bg_color                   = sanitize_text_field( $_POST[ 'rexvs_tooltip_bg_color' ] );
		$rexvs_swatches_font_size                 = sanitize_text_field( $_POST[ 'rexvs_swatches_font_size' ] );
		$rexvs_swatches_bg_color                  = sanitize_text_field( $_POST[ 'rexvs_swatches_bg_color' ] );
		$rexvs_swatches_color                     = sanitize_text_field( $_POST[ 'rexvs_swatches_color' ] );
		$rexvs_swatches_border                    = sanitize_text_field( $_POST[ 'rexvs_swatches_border' ] );
		$rexvs_swatches_border_size               = sanitize_text_field( $_POST[ 'rexvs_swatches_border_size' ] );
		$rexvs_swatches_border_style              = sanitize_text_field( $_POST[ 'rexvs_swatches_border_style' ] );
		$rexvs_swatches_border_color              = sanitize_text_field( $_POST[ 'rexvs_swatches_border_color' ] );
		$rexvs_seltd_swatches_bg_color            = sanitize_text_field( $_POST[ 'rexvs_seltd_swatches_bg_color' ] );
		$rexvs_seltd_swatches_color               = sanitize_text_field( $_POST[ 'rexvs_seltd_swatches_color' ] );
		$rexvs_seltd_swatches_border_size         = sanitize_text_field( $_POST[ 'rexvs_seltd_swatches_border_size' ] );
		$rexvs_seltd_swatches_border_color        = sanitize_text_field( $_POST[ 'rexvs_seltd_swatches_border_color' ] );
		$rexvs_hvr_swatches_bg_color              = sanitize_text_field( $_POST[ 'rexvs_hvr_swatches_bg_color' ] );
		$rexvs_hvr_swatches_color                 = sanitize_text_field( $_POST[ 'rexvs_hvr_swatches_color' ] );
		$rexvs_hvr_swatches_border_size           = sanitize_text_field( $_POST[ 'rexvs_hvr_swatches_border_size' ] );
		$rexvs_hvr_swatches_border_color          = sanitize_text_field( $_POST[ 'rexvs_hvr_swatches_border_color' ] );
		$rexvs_clr_btn_height                     = sanitize_text_field( $_POST[ 'rexvs_clr_btn_height' ] );
		$rexvs_clr_btn_width                      = sanitize_text_field( $_POST[ 'rexvs_clr_btn_width' ] );
		$rexvs_clr_btn_font_size                  = sanitize_text_field( $_POST[ 'rexvs_clr_btn_font_size' ] );
		$rexvs_clr_btn_radius                     = sanitize_text_field( $_POST[ 'rexvs_clr_btn_radius' ] );
		$rexvs_clr_btn_bg_color                   = sanitize_text_field( $_POST[ 'rexvs_clr_btn_bg_color' ] );
		$rexvs_clr_btn_color                      = sanitize_text_field( $_POST[ 'rexvs_clr_btn_color' ] );
		$rexvs_shop_hvr_swatches_bg_color         = sanitize_text_field( $_POST[ 'rexvs_shop_hvr_swatches_bg_color' ] );
		$rexvs_shop_hvr_swatches_color            = sanitize_text_field( $_POST[ 'rexvs_shop_hvr_swatches_color' ] );
		$rexvs_shop_seltd_swatches_bg_color       = sanitize_text_field( $_POST[ 'rexvs_shop_seltd_swatches_bg_color' ] );
		$rexvs_shop_seltd_swatches_color          = sanitize_text_field( $_POST[ 'rexvs_shop_seltd_swatches_color' ] );
		$rexvs_shop_swatch_alignment              = sanitize_text_field( $_POST[ 'rexvs_shop_swatch_alignment' ] );
		$rexvs_color_shape_style                  = sanitize_text_field( $_POST[ 'rexvs_color_shape_style' ] );
		$rexvs_color_shape_height                 = sanitize_text_field( $_POST[ 'rexvs_color_shape_height' ] );
		$rexvs_color_shape_width                  = sanitize_text_field( $_POST[ 'rexvs_color_shape_width' ] );
		$rexvs_color_tooltip                      = sanitize_text_field( $_POST[ 'rexvs_color_tooltip' ] );
		$rexvs_color_tooltip_fnt_size             = sanitize_text_field( $_POST[ 'rexvs_color_tooltip_fnt_size' ] );
		$rexvs_color_tooltip_color                = sanitize_text_field( $_POST[ 'rexvs_color_tooltip_color' ] );
		$rexvs_color_tooltip_bg_color             = sanitize_text_field( $_POST[ 'rexvs_color_tooltip_bg_color' ] );
		$rexvs_color_swatches_font_size           = sanitize_text_field( $_POST[ 'rexvs_color_swatches_font_size' ] );
		$rexvs_color_swatches_bg_color            = sanitize_text_field( $_POST[ 'rexvs_color_swatches_bg_color' ] );
		$rexvs_color_swatches_color               = sanitize_text_field( $_POST[ 'rexvs_color_swatches_color' ] );
		$rexvs_color_swatches_border              = sanitize_text_field( $_POST[ 'rexvs_color_swatches_border' ] );
		$rexvs_color_swatches_border_size         = sanitize_text_field( $_POST[ 'rexvs_color_swatches_border_size' ] );
		$rexvs_color_swatches_border_style        = sanitize_text_field( $_POST[ 'rexvs_color_swatches_border_style' ] );
		$rexvs_color_swatches_border_color        = sanitize_text_field( $_POST[ 'rexvs_color_swatches_border_color' ] );
		$rexvs_color_seltd_swatches_bg_color      = sanitize_text_field( $_POST[ 'rexvs_color_seltd_swatches_bg_color' ] );
		$rexvs_color_seltd_swatches_color         = sanitize_text_field( $_POST[ 'rexvs_color_seltd_swatches_color' ] );
		$rexvs_color_seltd_swatches_border_size   = sanitize_text_field( $_POST[ 'rexvs_color_seltd_swatches_border_size' ] );
		$rexvs_color_seltd_swatches_border_color  = sanitize_text_field( $_POST[ 'rexvs_color_seltd_swatches_border_color' ] );
		$rexvs_color_hvr_swatches_bg_color        = sanitize_text_field( $_POST[ 'rexvs_color_hvr_swatches_bg_color' ] );
		$rexvs_color_hvr_swatches_color           = sanitize_text_field( $_POST[ 'rexvs_color_hvr_swatches_color' ] );
		$rexvs_color_hvr_swatches_border_size     = sanitize_text_field( $_POST[ 'rexvs_color_hvr_swatches_border_size' ] );
		$rexvs_color_hvr_swatches_border_color    = sanitize_text_field( $_POST[ 'rexvs_color_hvr_swatches_border_color' ] );
		$rexvs_color_shop_shape_height            = sanitize_text_field( $_POST[ 'rexvs_color_shop_shape_height' ] );
		$rexvs_color_shop_shape_width             = sanitize_text_field( $_POST[ 'rexvs_color_shop_shape_width' ] );
		$rexvs_color_shop_swatches_font_size      = sanitize_text_field( $_POST[ 'rexvs_color_shop_swatches_font_size' ] );
		$rexvs_image_shape_style                  = sanitize_text_field( $_POST[ 'rexvs_image_shape_style' ] );
		$rexvs_image_shape_height                 = sanitize_text_field( $_POST[ 'rexvs_image_shape_height' ] );
		$rexvs_image_shape_width                  = sanitize_text_field( $_POST[ 'rexvs_image_shape_width' ] );
		$rexvs_image_tooltip                      = sanitize_text_field( $_POST[ 'rexvs_image_tooltip' ] );
		$rexvs_image_tooltip_fnt_size             = sanitize_text_field( $_POST[ 'rexvs_image_tooltip_fnt_size' ] );
		$rexvs_image_tooltip_color                = sanitize_text_field( $_POST[ 'rexvs_image_tooltip_color' ] );
		$rexvs_image_tooltip_bg_color             = sanitize_text_field( $_POST[ 'rexvs_image_tooltip_bg_color' ] );
		$rexvs_image_swatches_font_size           = sanitize_text_field( $_POST[ 'rexvs_image_swatches_font_size' ] );
		$rexvs_image_swatches_bg_color            = sanitize_text_field( $_POST[ 'rexvs_image_swatches_bg_color' ] );
		$rexvs_image_swatches_color               = sanitize_text_field( $_POST[ 'rexvs_image_swatches_color' ] );
		$rexvs_image_swatches_border              = sanitize_text_field( $_POST[ 'rexvs_image_swatches_border' ] );
		$rexvs_image_swatches_border_size         = sanitize_text_field( $_POST[ 'rexvs_image_swatches_border_size' ] );
		$rexvs_image_swatches_border_style        = sanitize_text_field( $_POST[ 'rexvs_image_swatches_border_style' ] );
		$rexvs_image_swatches_border_color        = sanitize_text_field( $_POST[ 'rexvs_image_swatches_border_color' ] );
		$rexvs_image_seltd_swatches_bg_color      = sanitize_text_field( $_POST[ 'rexvs_image_seltd_swatches_bg_color' ] );
		$rexvs_image_seltd_swatches_color         = sanitize_text_field( $_POST[ 'rexvs_image_seltd_swatches_color' ] );
		$rexvs_image_seltd_swatches_border_size   = sanitize_text_field( $_POST[ 'rexvs_image_seltd_swatches_border_size' ] );
		$rexvs_image_seltd_swatches_border_color  = sanitize_text_field( $_POST[ 'rexvs_image_seltd_swatches_border_color' ] );
		$rexvs_image_hvr_swatches_bg_color        = sanitize_text_field( $_POST[ 'rexvs_image_hvr_swatches_bg_color' ] );
		$rexvs_image_hvr_swatches_color           = sanitize_text_field( $_POST[ 'rexvs_image_hvr_swatches_color' ] );
		$rexvs_image_hvr_swatches_border_size     = sanitize_text_field( $_POST[ 'rexvs_image_hvr_swatches_border_size' ] );
		$rexvs_image_hvr_swatches_border_color    = sanitize_text_field( $_POST[ 'rexvs_image_hvr_swatches_border_color' ] );
		$rexvs_image_shop_shape_height            = sanitize_text_field( $_POST[ 'rexvs_image_shop_shape_height' ] );
		$rexvs_image_shop_shape_width             = sanitize_text_field( $_POST[ 'rexvs_image_shop_shape_width' ] );
		$rexvs_image_shop_swatches_font_size      = sanitize_text_field( $_POST[ 'rexvs_image_shop_swatches_font_size' ] );
		$rexvs_label_shape_style                  = sanitize_text_field( $_POST[ 'rexvs_label_shape_style' ] );
		$rexvs_label_shape_height                 = sanitize_text_field( $_POST[ 'rexvs_label_shape_height' ] );
		$rexvs_label_shape_width                  = sanitize_text_field( $_POST[ 'rexvs_label_shape_width' ] );
		$rexvs_label_tooltip                      = sanitize_text_field( $_POST[ 'rexvs_label_tooltip' ] );
		$rexvs_label_tooltip_fnt_size             = sanitize_text_field( $_POST[ 'rexvs_label_tooltip_fnt_size' ] );
		$rexvs_label_tooltip_color                = sanitize_text_field( $_POST[ 'rexvs_label_tooltip_color' ] );
		$rexvs_label_tooltip_bg_color             = sanitize_text_field( $_POST[ 'rexvs_label_tooltip_bg_color' ] );
		$rexvs_label_swatches_font_size           = sanitize_text_field( $_POST[ 'rexvs_label_swatches_font_size' ] );
		$rexvs_label_swatches_bg_color            = sanitize_text_field( $_POST[ 'rexvs_label_swatches_bg_color' ] );
		$rexvs_label_swatches_color               = sanitize_text_field( $_POST[ 'rexvs_label_swatches_color' ] );
		$rexvs_label_top_padding                  = sanitize_text_field( $_POST[ 'rexvs_label_top_padding' ] );
		$rexvs_label_right_padding                = sanitize_text_field( $_POST[ 'rexvs_label_right_padding' ] );
		$rexvs_label_bottom_padding               = sanitize_text_field( $_POST[ 'rexvs_label_bottom_padding' ] );
		$rexvs_label_left_padding                 = sanitize_text_field( $_POST[ 'rexvs_label_left_padding' ] );
		$rexvs_label_swatches_border              = sanitize_text_field( $_POST[ 'rexvs_label_swatches_border' ] );
		$rexvs_label_swatches_border_size         = sanitize_text_field( $_POST[ 'rexvs_label_swatches_border_size' ] );
		$rexvs_label_swatches_border_style        = sanitize_text_field( $_POST[ 'rexvs_label_swatches_border_style' ] );
		$rexvs_label_swatches_border_color        = sanitize_text_field( $_POST[ 'rexvs_label_swatches_border_color' ] );
		$rexvs_label_seltd_swatches_bg_color      = sanitize_text_field( $_POST[ 'rexvs_label_seltd_swatches_bg_color' ] );
		$rexvs_label_seltd_swatches_color         = sanitize_text_field( $_POST[ 'rexvs_label_seltd_swatches_color' ] );
		$rexvs_label_seltd_swatches_border_size   = sanitize_text_field( $_POST[ 'rexvs_label_seltd_swatches_border_size' ] );
		$rexvs_label_seltd_swatches_border_color  = sanitize_text_field( $_POST[ 'rexvs_label_seltd_swatches_border_color' ] );
		$rexvs_label_hvr_swatches_bg_color        = sanitize_text_field( $_POST[ 'rexvs_label_hvr_swatches_bg_color' ] );
		$rexvs_label_hvr_swatches_color           = sanitize_text_field( $_POST[ 'rexvs_label_hvr_swatches_color' ] );
		$rexvs_label_hvr_swatches_border_size     = sanitize_text_field( $_POST[ 'rexvs_label_hvr_swatches_border_size' ] );
		$rexvs_label_hvr_swatches_border_color    = sanitize_text_field( $_POST[ 'rexvs_label_hvr_swatches_border_color' ] );
		$rexvs_label_shop_shape_height            = sanitize_text_field( $_POST[ 'rexvs_label_shop_shape_height' ] );
		$rexvs_label_shop_shape_width             = sanitize_text_field( $_POST[ 'rexvs_label_shop_shape_width' ] );
		$rexvs_label_shop_swatches_font_size      = sanitize_text_field( $_POST[ 'rexvs_label_shop_swatches_font_size' ] );
		$rexvs_select_shape_style                 = sanitize_text_field( $_POST[ 'rexvs_select_shape_style' ] );
		$rexvs_select_shape_height                = sanitize_text_field( $_POST[ 'rexvs_select_shape_height' ] );
		$rexvs_select_shape_width                 = sanitize_text_field( $_POST[ 'rexvs_select_shape_width' ] );
		$rexvs_select_tooltip                     = sanitize_text_field( $_POST[ 'rexvs_select_tooltip' ] );
		$rexvs_select_tooltip_fnt_size            = sanitize_text_field( $_POST[ 'rexvs_select_tooltip_fnt_size' ] );
		$rexvs_select_tooltip_color               = sanitize_text_field( $_POST[ 'rexvs_select_tooltip_color' ] );
		$rexvs_select_tooltip_bg_color            = sanitize_text_field( $_POST[ 'rexvs_select_tooltip_bg_color' ] );
		$rexvs_select_swatches_font_size          = sanitize_text_field( $_POST[ 'rexvs_select_swatches_font_size' ] );
		$rexvs_select_swatches_bg_color           = sanitize_text_field( $_POST[ 'rexvs_select_swatches_bg_color' ] );
		$rexvs_select_swatches_color              = sanitize_text_field( $_POST[ 'rexvs_select_swatches_color' ] );
		$rexvs_select_top_padding                 = sanitize_text_field( $_POST[ 'rexvs_select_top_padding' ] );
		$rexvs_select_right_padding               = sanitize_text_field( $_POST[ 'rexvs_select_right_padding' ] );
		$rexvs_select_bottom_padding              = sanitize_text_field( $_POST[ 'rexvs_select_bottom_padding' ] );
		$rexvs_select_left_padding                = sanitize_text_field( $_POST[ 'rexvs_select_left_padding' ] );
		$rexvs_select_swatches_border             = sanitize_text_field( $_POST[ 'rexvs_select_swatches_border' ] );
		$rexvs_select_swatches_border_size        = sanitize_text_field( $_POST[ 'rexvs_select_swatches_border_size' ] );
		$rexvs_select_swatches_border_style       = sanitize_text_field( $_POST[ 'rexvs_select_swatches_border_style' ] );
		$rexvs_select_swatches_border_color       = sanitize_text_field( $_POST[ 'rexvs_select_swatches_border_color' ] );
		$rexvs_select_seltd_swatches_bg_color     = sanitize_text_field( $_POST[ 'rexvs_select_seltd_swatches_bg_color' ] );
		$rexvs_select_seltd_swatches_color        = sanitize_text_field( $_POST[ 'rexvs_select_seltd_swatches_color' ] );
		$rexvs_select_seltd_swatches_border_size  = sanitize_text_field( $_POST[ 'rexvs_select_seltd_swatches_border_size' ] );
		$rexvs_select_seltd_swatches_border_color = sanitize_text_field( $_POST[ 'rexvs_select_seltd_swatches_border_color' ] );
		$rexvs_select_hvr_swatches_bg_color       = sanitize_text_field( $_POST[ 'rexvs_select_hvr_swatches_bg_color' ] );
		$rexvs_select_hvr_swatches_color          = sanitize_text_field( $_POST[ 'rexvs_select_hvr_swatches_color' ] );
		$rexvs_select_hvr_swatches_border_size    = sanitize_text_field( $_POST[ 'rexvs_select_hvr_swatches_border_size' ] );
		$rexvs_select_hvr_swatches_border_color   = sanitize_text_field( $_POST[ 'rexvs_select_hvr_swatches_border_color' ] );
		$rexvs_select_shop_swatches_font_size     = sanitize_text_field( $_POST[ 'rexvs_select_shop_swatches_font_size' ] );
		$rexvs_select_shop_top_padding            = sanitize_text_field( $_POST[ 'rexvs_select_shop_top_padding' ] );
		$rexvs_select_shop_right_padding          = sanitize_text_field( $_POST[ 'rexvs_select_shop_right_padding' ] );
		$rexvs_select_shop_bottom_padding         = sanitize_text_field( $_POST[ 'rexvs_select_shop_bottom_padding' ] );
		$rexvs_select_shop_left_padding           = sanitize_text_field( $_POST[ 'rexvs_select_shop_left_padding' ] );
		$rexvs_select_shop_radius                 = sanitize_text_field( $_POST[ 'rexvs_select_shop_radius' ] );

		$setup_data = array(
			'rexvs_default_dropdown_to_button'         => $rexvs_default_dropdown_to_button,
			'rexvs_delete_data'                        => $rexvs_delete_data,
			'rexvs_disable_stylesheet'                 => $rexvs_disable_stylesheet,
			'rexvs_enable_customAttribute'             => $rexvs_enable_customAttribute,
			'rexvs_individual_attr_style'              => $rexvs_individual_attr_style,
			'rexvs_shop_swatch'                        => $rexvs_shop_swatch,
			'rexvs_tooltip'                            => $rexvs_tooltip,
			'rexvs_shape_style'                        => $rexvs_shape_style,
			'rexvs_shape_height'                       => $rexvs_shape_height,
			'rexvs_shape_width'                        => $rexvs_shape_width,
			'rexvs_tooltip_fnt_size'                   => $rexvs_tooltip_fnt_size,
			'rexvs_tooltip_color'                      => $rexvs_tooltip_color,
			'rexvs_tooltip_bg_color'                   => $rexvs_tooltip_bg_color,
			'rexvs_swatches_font_size'                 => $rexvs_swatches_font_size,
			'rexvs_swatches_bg_color'                  => $rexvs_swatches_bg_color,
			'rexvs_swatches_color'                     => $rexvs_swatches_color,
			'rexvs_swatches_border'                    => $rexvs_swatches_border,
			'rexvs_swatches_border_size'               => $rexvs_swatches_border_size,
			'rexvs_swatches_border_style'              => $rexvs_swatches_border_style,
			'rexvs_swatches_border_color'              => $rexvs_swatches_border_color,
			'rexvs_seltd_swatches_bg_color'            => $rexvs_seltd_swatches_bg_color,
			'rexvs_seltd_swatches_color'               => $rexvs_seltd_swatches_color,
			'rexvs_seltd_swatches_border_size'         => $rexvs_seltd_swatches_border_size,
			'rexvs_seltd_swatches_border_color'        => $rexvs_seltd_swatches_border_color,
			'rexvs_hvr_swatches_bg_color'              => $rexvs_hvr_swatches_bg_color,
			'rexvs_hvr_swatches_color'                 => $rexvs_hvr_swatches_color,
			'rexvs_hvr_swatches_border_size'           => $rexvs_hvr_swatches_border_size,
			'rexvs_hvr_swatches_border_color'          => $rexvs_hvr_swatches_border_color,
			'rexvs_clr_btn_height'                     => $rexvs_clr_btn_height,
			'rexvs_clr_btn_width'                      => $rexvs_clr_btn_width,
			'rexvs_clr_btn_font_size'                  => $rexvs_clr_btn_font_size,
			'rexvs_clr_btn_radius'                     => $rexvs_clr_btn_radius,
			'rexvs_clr_btn_bg_color'                   => $rexvs_clr_btn_bg_color,
			'rexvs_clr_btn_color'                      => $rexvs_clr_btn_color,
			'rexvs_shop_hvr_swatches_bg_color'         => $rexvs_shop_hvr_swatches_bg_color,
			'rexvs_shop_hvr_swatches_color'            => $rexvs_shop_hvr_swatches_color,
			'rexvs_shop_seltd_swatches_bg_color'       => $rexvs_shop_seltd_swatches_bg_color,
			'rexvs_shop_seltd_swatches_color'          => $rexvs_shop_seltd_swatches_color,
			'rexvs_shop_swatch_alignment'              => $rexvs_shop_swatch_alignment,
			'rexvs_color_tooltip'                      => $rexvs_color_tooltip,
			'rexvs_color_shape_style'                  => $rexvs_color_shape_style,
			'rexvs_color_shape_height'                 => $rexvs_color_shape_height,
			'rexvs_color_shape_width'                  => $rexvs_color_shape_width,
			'rexvs_color_tooltip_fnt_size'             => $rexvs_color_tooltip_fnt_size,
			'rexvs_color_tooltip_color'                => $rexvs_color_tooltip_color,
			'rexvs_color_tooltip_bg_color'             => $rexvs_color_tooltip_bg_color,
			'rexvs_color_swatches_font_size'           => $rexvs_color_swatches_font_size,
			'rexvs_color_swatches_bg_color'            => $rexvs_color_swatches_bg_color,
			'rexvs_color_swatches_color'               => $rexvs_color_swatches_color,
			'rexvs_color_swatches_border'              => $rexvs_color_swatches_border,
			'rexvs_color_swatches_border_size'         => $rexvs_color_swatches_border_size,
			'rexvs_color_swatches_border_style'        => $rexvs_color_swatches_border_style,
			'rexvs_color_swatches_border_color'        => $rexvs_color_swatches_border_color,
			'rexvs_color_seltd_swatches_bg_color'      => $rexvs_color_seltd_swatches_bg_color,
			'rexvs_color_seltd_swatches_color'         => $rexvs_color_seltd_swatches_color,
			'rexvs_color_seltd_swatches_border_size'   => $rexvs_color_seltd_swatches_border_size,
			'rexvs_color_seltd_swatches_border_color'  => $rexvs_color_seltd_swatches_border_color,
			'rexvs_color_hvr_swatches_bg_color'        => $rexvs_color_hvr_swatches_bg_color,
			'rexvs_color_hvr_swatches_color'           => $rexvs_color_hvr_swatches_color,
			'rexvs_color_hvr_swatches_border_size'     => $rexvs_color_hvr_swatches_border_size,
			'rexvs_color_hvr_swatches_border_color'    => $rexvs_color_hvr_swatches_border_color,
			'rexvs_color_shop_shape_height'            => $rexvs_color_shop_shape_height,
			'rexvs_color_shop_shape_width'             => $rexvs_color_shop_shape_width,
			'rexvs_color_shop_swatches_font_size'      => $rexvs_color_shop_swatches_font_size,
			'rexvs_image_tooltip'                      => $rexvs_image_tooltip,
			'rexvs_image_shape_style'                  => $rexvs_image_shape_style,
			'rexvs_image_shape_height'                 => $rexvs_image_shape_height,
			'rexvs_image_shape_width'                  => $rexvs_image_shape_width,
			'rexvs_image_tooltip_fnt_size'             => $rexvs_image_tooltip_fnt_size,
			'rexvs_image_tooltip_color'                => $rexvs_image_tooltip_color,
			'rexvs_image_tooltip_bg_color'             => $rexvs_image_tooltip_bg_color,
			'rexvs_image_swatches_font_size'           => $rexvs_image_swatches_font_size,
			'rexvs_image_swatches_bg_color'            => $rexvs_image_swatches_bg_color,
			'rexvs_image_swatches_color'               => $rexvs_image_swatches_color,
			'rexvs_image_swatches_border'              => $rexvs_image_swatches_border,
			'rexvs_image_swatches_border_size'         => $rexvs_image_swatches_border_size,
			'rexvs_image_swatches_border_style'        => $rexvs_image_swatches_border_style,
			'rexvs_image_swatches_border_color'        => $rexvs_image_swatches_border_color,
			'rexvs_image_seltd_swatches_bg_color'      => $rexvs_image_seltd_swatches_bg_color,
			'rexvs_image_seltd_swatches_color'         => $rexvs_image_seltd_swatches_color,
			'rexvs_image_seltd_swatches_border_size'   => $rexvs_image_seltd_swatches_border_size,
			'rexvs_image_seltd_swatches_border_color'  => $rexvs_image_seltd_swatches_border_color,
			'rexvs_image_hvr_swatches_bg_color'        => $rexvs_image_hvr_swatches_bg_color,
			'rexvs_image_hvr_swatches_color'           => $rexvs_image_hvr_swatches_color,
			'rexvs_image_hvr_swatches_border_size'     => $rexvs_image_hvr_swatches_border_size,
			'rexvs_image_hvr_swatches_border_color'    => $rexvs_image_hvr_swatches_border_color,
			'rexvs_image_shop_shape_height'            => $rexvs_image_shop_shape_height,
			'rexvs_image_shop_shape_width'             => $rexvs_image_shop_shape_width,
			'rexvs_image_shop_swatches_font_size'      => $rexvs_image_shop_swatches_font_size,
			'rexvs_label_tooltip'                      => $rexvs_label_tooltip,
			'rexvs_label_shape_style'                  => $rexvs_label_shape_style,
			'rexvs_label_shape_height'                 => $rexvs_label_shape_height,
			'rexvs_label_shape_width'                  => $rexvs_label_shape_width,
			'rexvs_label_tooltip_fnt_size'             => $rexvs_label_tooltip_fnt_size,
			'rexvs_label_tooltip_color'                => $rexvs_label_tooltip_color,
			'rexvs_label_tooltip_bg_color'             => $rexvs_label_tooltip_bg_color,
			'rexvs_label_swatches_font_size'           => $rexvs_label_swatches_font_size,
			'rexvs_label_swatches_bg_color'            => $rexvs_label_swatches_bg_color,
			'rexvs_label_swatches_color'               => $rexvs_label_swatches_color,
			'rexvs_label_top_padding'                  => $rexvs_label_top_padding,
			'rexvs_label_right_padding'                => $rexvs_label_right_padding,
			'rexvs_label_bottom_padding'               => $rexvs_label_bottom_padding,
			'rexvs_label_left_padding'                 => $rexvs_label_left_padding,
			'rexvs_label_swatches_border'              => $rexvs_label_swatches_border,
			'rexvs_label_swatches_border_size'         => $rexvs_label_swatches_border_size,
			'rexvs_label_swatches_border_style'        => $rexvs_label_swatches_border_style,
			'rexvs_label_swatches_border_color'        => $rexvs_label_swatches_border_color,
			'rexvs_label_seltd_swatches_bg_color'      => $rexvs_label_seltd_swatches_bg_color,
			'rexvs_label_seltd_swatches_color'         => $rexvs_label_seltd_swatches_color,
			'rexvs_label_seltd_swatches_border_size'   => $rexvs_label_seltd_swatches_border_size,
			'rexvs_label_seltd_swatches_border_color'  => $rexvs_label_seltd_swatches_border_color,
			'rexvs_label_hvr_swatches_bg_color'        => $rexvs_label_hvr_swatches_bg_color,
			'rexvs_label_hvr_swatches_color'           => $rexvs_label_hvr_swatches_color,
			'rexvs_label_hvr_swatches_border_size'     => $rexvs_label_hvr_swatches_border_size,
			'rexvs_label_hvr_swatches_border_color'    => $rexvs_label_hvr_swatches_border_color,
			'rexvs_label_shop_shape_height'            => $rexvs_label_shop_shape_height,
			'rexvs_label_shop_shape_width'             => $rexvs_label_shop_shape_width,
			'rexvs_label_shop_swatches_font_size'      => $rexvs_label_shop_swatches_font_size,
			'rexvs_select_tooltip'                     => $rexvs_select_tooltip,
			'rexvs_select_shape_style'                 => $rexvs_select_shape_style,
			'rexvs_select_shape_height'                => $rexvs_select_shape_height,
			'rexvs_select_shape_width'                 => $rexvs_select_shape_width,
			'rexvs_select_tooltip_fnt_size'            => $rexvs_select_tooltip_fnt_size,
			'rexvs_select_tooltip_color'               => $rexvs_select_tooltip_color,
			'rexvs_select_tooltip_bg_color'            => $rexvs_select_tooltip_bg_color,
			'rexvs_select_swatches_font_size'          => $rexvs_select_swatches_font_size,
			'rexvs_select_swatches_bg_color'           => $rexvs_select_swatches_bg_color,
			'rexvs_select_swatches_color'              => $rexvs_select_swatches_color,
			'rexvs_select_top_padding'                 => $rexvs_select_top_padding,
			'rexvs_select_right_padding'               => $rexvs_select_right_padding,
			'rexvs_select_bottom_padding'              => $rexvs_select_bottom_padding,
			'rexvs_select_left_padding'                => $rexvs_select_left_padding,
			'rexvs_select_swatches_border'             => $rexvs_select_swatches_border,
			'rexvs_select_swatches_border_size'        => $rexvs_select_swatches_border_size,
			'rexvs_select_swatches_border_style'       => $rexvs_select_swatches_border_style,
			'rexvs_select_swatches_border_color'       => $rexvs_select_swatches_border_color,
			'rexvs_select_seltd_swatches_bg_color'     => $rexvs_select_seltd_swatches_bg_color,
			'rexvs_select_seltd_swatches_color'        => $rexvs_select_seltd_swatches_color,
			'rexvs_select_seltd_swatches_border_size'  => $rexvs_select_seltd_swatches_border_size,
			'rexvs_select_seltd_swatches_border_color' => $rexvs_select_seltd_swatches_border_color,
			'rexvs_select_hvr_swatches_bg_color'       => $rexvs_select_hvr_swatches_bg_color,
			'rexvs_select_hvr_swatches_color'          => $rexvs_select_hvr_swatches_color,
			'rexvs_select_hvr_swatches_border_size'    => $rexvs_select_hvr_swatches_border_size,
			'rexvs_select_hvr_swatches_border_color'   => $rexvs_select_hvr_swatches_border_color,
			'rexvs_select_shop_swatches_font_size'     => $rexvs_select_shop_swatches_font_size,
			'rexvs_select_shop_top_padding'            => $rexvs_select_shop_top_padding,
			'rexvs_select_shop_right_padding'          => $rexvs_select_shop_right_padding,
			'rexvs_select_shop_bottom_padding'         => $rexvs_select_shop_bottom_padding,
			'rexvs_select_shop_left_padding'           => $rexvs_select_shop_left_padding,
			'rexvs_select_shop_radius'                 => $rexvs_select_shop_radius,
		);

		$data = serialize( $setup_data );
		update_option( 'rexvs_setup_data', $data );

		$response = array(
			'status'  => 'success',
			'message' => 'Successfully Saved',
		);
		wp_send_json( $response );
	}

	/**
	 * @desc processing the custom attribute values and push the updated
	 * attribute into the database with a new meta_key "_attribute_values"
	 * This function is called from an ajax request.
	 */
	public function rexvs_custom_attr_submit() {
		$post_id                  = sanitize_text_field( $_POST[ 'post_id' ] );
		$custom_attr_types        = isset( $_POST[ 'custom_attribute_type' ] ) ? $_POST[ 'custom_attribute_type' ] : '';
		$custom_parent_attr_names = isset( $_POST[ 'parent_attr_names' ] ) ? $_POST[ 'parent_attr_names' ] : '';
		$custom_child_attr_names  = isset( $_POST[ 'child_attr_names' ] ) ? $_POST[ 'child_attr_names' ] : '';
		$image_urls               = isset( $_POST[ 'image_urls' ] ) ? $_POST[ 'image_urls' ] : '';
		$color_ids                = isset( $_POST[ 'color_values' ] ) ? $_POST[ 'color_values' ] : '';
		$labels                   = isset( $_POST[ 'label_values' ] ) ? $_POST[ 'label_values' ] : '';

		$custom_attr_setup_data = array();
		$attr_type_index        = 0;

		if ( $custom_parent_attr_names !== '' ) {
			foreach ( $custom_parent_attr_names as $custom_parent_attr_name ) {
				$terms                 = array();
				$child_attr_term_index = 0;
				if ( $custom_attr_types[ $attr_type_index ] !== 'select' ) {
					foreach ( $custom_child_attr_names as $custom_child_attr_name ) {
						if ( $custom_child_attr_name[ 0 ] === $custom_parent_attr_name ) {
							if ( $custom_attr_types[ $attr_type_index ] === 'color' ) {
								$terms[ $custom_child_attr_name[ 1 ] ] = array(
									'color_id' => $color_ids[ $child_attr_term_index++ ],
									'slug'     => $custom_child_attr_name[ 1 ]
								);
							}
							elseif ( $custom_attr_types[ $attr_type_index ] === 'image' ) {
								if ( $custom_parent_attr_name === $image_urls[ $child_attr_term_index ][ 0 ] ) {
									$terms[ $custom_child_attr_name[ 1 ] ] = array(
										'image_url' => $image_urls[ $child_attr_term_index ][ 1 ],
										'slug'      => $custom_child_attr_name[ 1 ]
									);
								}
								$child_attr_term_index++;
							}
							elseif ( $custom_attr_types[ $attr_type_index ] === 'label' ) {
								$terms[ $custom_child_attr_name[ 1 ] ] = array(
									'label' => $labels[ $child_attr_term_index++ ],
									'slug'  => $custom_child_attr_name[ 1 ]
								);
							}
						}
					}

					$custom_attr_setup_data[ $custom_parent_attr_name ] = array(
						'type'  => $custom_attr_types[ $attr_type_index++ ],
						'terms' => $terms
					);
				}
			}
		}

		if ( !empty( $custom_attr_setup_data ) ) {
			update_post_meta( $post_id, '_attribute_values', $custom_attr_setup_data );
		}
		else {
			delete_post_meta( $post_id, '_attribute_values' );
		}
		$response = array(
			'status'  => 'success',
			'message' => 'Successfully Saved',
		);
		wp_send_json( $response );
	}
}

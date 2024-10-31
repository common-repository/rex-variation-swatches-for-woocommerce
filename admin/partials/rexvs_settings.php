<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Variation_Swatches_For_Woocommerce
 * @subpackage Variation_Swatches_For_Woocommerce/admin/partials
 */
$rexvs_setup_data = unserialize( get_option( 'rexvs_setup_data' ) );
?>

<div class="rexvs-settings">
    <div class="circle-loading-section" id="circle-loading-section">
        <div class="circle-loading"></div>
    </div>

    <div id="rexvs-tabs" class="rexvs-tabs">
        <ul class="rexvs-tab-nav">
            <li class="logo"><img src="<?php
				echo REXVS_PLUGIN_DIR_URL . 'images/logo.png' ?>" alt="logo"></li>
            <li><a href="#general"><?php
					_e( 'General', 'rexvs' ); ?></a></li>
            <li><a href="#controls"><?php
					_e( 'Controls', 'rexvs' ); ?></a></li>
            <li><a href="#gopro"><?php
					_e( 'Go Pro', 'rexvs' ); ?></a></li>
        </ul>

        <div class="rexvs-tab-content">
            <div id="general" class="general">
                <div class="tab-content-header">
                    <h4><?php
						_e( 'General Settings', 'rexvs' ); ?></h4>
                </div>

                <div class="tab-content-wrapper">
                    <div class="rexvs-box global-style">
                        <div class="rexvs-divider-style">
                            <h2><?php
								_e( 'Swatches Global Style', 'rexvs' ); ?></h2>
                        </div>

                        <div class="rexvs-form-group shape-height">
                            <span class="label"><?php
	                            _e( 'Shape Height: ', 'rexvs' ); ?></span>
							<?php
							$rexvs_shape_height = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_shape_height' ] ) && $rexvs_setup_data[ 'rexvs_shape_height' ] != '' ) {
								$rexvs_shape_height = $rexvs_setup_data[ 'rexvs_shape_height' ];
							}
							?>
                            <input type="number" name="rexvs_shape_height" min="0" placeholder="45">
                            <span class="hints"><?php
								_e( 'px', 'rexvs' ); ?></span>
                        </div>

                        <div class="rexvs-form-group shape-width">
                            <span class="label"><?php
	                            _e( 'Shape Width: ', 'rexvs' ); ?></span>
							<?php
							$rexvs_shape_width = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_shape_width' ] ) ) {
								$rexvs_shape_width = $rexvs_setup_data[ 'rexvs_shape_width' ];
							}
							?>
                            <input type="number" name="rexvs_shape_width" min="0" placeholder="45">
                            <span class="hints"><?php
								_e( 'px', 'rexvs' ); ?></span>
                        </div>

                        <div class="rexvs-form-group shape-font-size">
                            <span class="label"><?php
	                            _e( 'Font Size: ', 'rexvs' ); ?></span>
							<?php
							$rexvs_swatches_font_size = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_swatches_font_size' ] ) ) {
								$rexvs_swatches_font_size = $rexvs_setup_data[ 'rexvs_swatches_font_size' ];
							}
							?>
                            <input type="number" name="rexvs_swatches_font_size" min="0" placeholder="11">
                            <span class="hints"><?php
								_e( 'px', 'rexvs' ); ?></span>
                        </div>

                        <div class="rexvs-form-group shape-bg-color picker-bottom">
                            <span class="label"><?php
	                            _e( 'Background Color: ', 'rexvs' ); ?></span>
							<?php
							$rexvs_swatches_bg_color = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_swatches_bg_color' ] ) ) {
								$rexvs_swatches_bg_color = $rexvs_setup_data[ 'rexvs_swatches_bg_color' ];
							}
							?>
                            <input type="text" name="rexvs_swatches_bg_color" value="<?php
							echo $rexvs_swatches_bg_color; ?>" class="rexsv-color-picker">
                        </div>

                        <div class="rexvs-form-group shape-font-color">
                            <span class="label"><?php
	                            _e( 'Font Color:', 'rexvs' ); ?> </span>
							<?php
							$rexvs_swatches_color = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_swatches_color' ] ) ) {
								$rexvs_swatches_color = $rexvs_setup_data[ 'rexvs_swatches_color' ];
							}
							?>
                            <input type="text" name="rexvs_swatches_color" value="<?php
							echo $rexvs_swatches_color; ?>" class="rexsv-color-picker">
                        </div>

                        <div class="rexvs-form-group shpae-style">
                            <span class="label"><?php
	                            _e( 'Shape Style: ', 'rexvs' ); ?></span>
                            <ul class="rexvs-radio">
								<?php
								if ( isset( $rexvs_setup_data[ 'rexvs_shape_style' ] ) && $rexvs_setup_data[ 'rexvs_shape_style' ] == 'squared' ) {
									?>
                                    <li>
                                        <input type="radio" name="rexvs_shape_style" id="rounded" value="rounded">
                                        <label for="rounded"><span></span><?php
											_e( 'Rounded: ', 'rexvs' ); ?></label>
                                    </li>
                                    <li>
                                        <input type="radio" name="rexvs_shape_style" id="squared" value="squared"
                                               checked>
                                        <label for="squared"><span></span><?php
											_e( 'Squared: ', 'rexvs' ); ?></label>
                                    </li>
									<?php
								}
								else {
									?>
                                    <li>
                                        <input type="radio" name="rexvs_shape_style" id="rounded" value="rounded"
                                               checked>
                                        <label for="rounded"><span></span><?php
											_e( 'Rounded: ', 'rexvs' ); ?></label>
                                    </li>
                                    <li>
                                        <input type="radio" name="rexvs_shape_style" id="squared" value="squared">
                                        <label for="squared"><span></span><?php
											_e( 'Squared: ', 'rexvs' ); ?></label>
                                    </li>
									<?php
								}
								?>
                            </ul>
                        </div>

                        <div class="rexvs-form-group shape-border-switch">
                            <span class="label"><?php
	                            _e( 'Border Enable/Disable:', 'rexvs' ); ?></span>
                            <div class="rexvs-switcher">
								<?php
								if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border' ] ) && $rexvs_setup_data[ 'rexvs_swatches_border' ] == 'on' ) {
									?>
                                    <input class="switch" id="rexvs_swatches_border" name="rexvs_swatches_border"
                                           type="checkbox" checked>
									<?php
								}
								else {
									?>
                                    <input class="switch" id="rexvs_swatches_border" name="rexvs_swatches_border"
                                           type="checkbox">
									<?php
								}
								?>
                                <label for="rexvs_swatches_border"></label>
                            </div>
                        </div>

                        <div class="rexvs-form-group tooltip-switch">
                            <span class="label"><?php
	                            _e( 'Tooltip Enable/Disable:', 'rexvs' ); ?></span>
                            <div class="rexvs-switcher">
								<?php
								if ( isset( $rexvs_setup_data[ 'rexvs_tooltip' ] ) && $rexvs_setup_data[ 'rexvs_tooltip' ] == 'on' ) {
									?>
                                    <input class="switch" id="rexvs_tooltip" name="rexvs_tooltip" type="checkbox"
                                           checked>
									<?php
								}
								else {
									?>
                                    <input class="switch" id="rexvs_tooltip" name="rexvs_tooltip" type="checkbox">
									<?php
								}
								?>

                                <label for="rexvs_tooltip"></label>
                            </div>
                        </div>

                        <div class="enabled-swatches-border enabled-global-swatches-border">
                            <div class="rexvs-form-group">
                                <span class="label"><?php
	                                _e( 'Border:', 'rexvs' ); ?></span>
                                <div class="border-style">
									<?php
									$rexvs_swatches_border_size  = 1;
									$rexvs_swatches_border_color = '';
									$rexvs_swatches_border_style = '';

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_size' ] ) && $rexvs_setup_data[ 'rexvs_swatches_border_size' ] != '' ) {
										$rexvs_swatches_border_size = $rexvs_setup_data[ 'rexvs_swatches_border_size' ];
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_color' ] ) ) {
										$rexvs_swatches_border_color = $rexvs_setup_data[ 'rexvs_swatches_border_color' ];
									}

									if ( isset( $rexvs_setup_data[ 'rexvs_swatches_border_style' ] ) ) {
										$rexvs_swatches_border_style = $rexvs_setup_data[ 'rexvs_swatches_border_style' ];
									}
									?>
                                    <input type="number" name="rexvs_swatches_border_size" value="<?php
									echo $rexvs_swatches_border_size; ?>" min="0">
                                    <span class="hints"><?php
										_e( 'px', 'rexvs' ); ?></span>
                                    <select name="rexvs_swatches_border_style">
                                        <option value="solid" <?php
										echo $rexvs_swatches_border_style == 'solid' ? 'selected' : ''; ?>><?php
											_e( 'Solid: ', 'rexvs' ); ?></option>
                                        <option value="dashed" <?php
										echo $rexvs_swatches_border_style == 'dashed' ? 'selected' : ''; ?>><?php
											_e( 'Dashed: ', 'rexvs' ); ?></option>
                                        <option value="dotted" <?php
										echo $rexvs_swatches_border_style == 'dotted' ? 'selected' : ''; ?>><?php
											_e( 'Dotted: ', 'rexvs' ); ?></option>
                                        <option value="double" <?php
										echo $rexvs_swatches_border_style == 'double' ? 'selected' : ''; ?>><?php
											_e( 'Double: ', 'rexvs' ); ?></option>
                                    </select>
                                    <input type="text" name="rexvs_swatches_border_color" value="<?php
									echo $rexvs_swatches_border_color; ?>" class="rexsv-color-picker">
                                </div>
                            </div>
                        </div>

                        <div class="enabled-tooltip enabled-global-tooltip">
                            <div class="tooltip-field-wrapper">
                                <div class="rexvs-form-group">
                                    <span class="label"><?php
	                                    _e( 'Tooltip Font Size:', 'rexvs' ); ?> </span>
									<?php
									$rexvs_tooltip_fnt_size = '';
									if ( isset( $rexvs_setup_data[ 'rexvs_tooltip_fnt_size' ] ) ) {
										$rexvs_tooltip_fnt_size = $rexvs_setup_data[ 'rexvs_tooltip_fnt_size' ];
									}
									?>
                                    <input type="number" name="rexvs_tooltip_fnt_size" value="<?php
									echo $rexvs_tooltip_fnt_size; ?>" min="0">
                                </div>

                                <div class="rexvs-form-group">
                                    <span class="label"><?php
	                                    _e( 'Tooltip Text Color: ', 'rexvs' ); ?></span>
									<?php
									$rexvs_tooltip_color = '';
									if ( isset( $rexvs_setup_data[ 'rexvs_tooltip_color' ] ) ) {
										$rexvs_tooltip_color = $rexvs_setup_data[ 'rexvs_tooltip_color' ];
									}
									?>
                                    <input type="text" name="rexvs_tooltip_color" value="<?php
									echo $rexvs_tooltip_color; ?>" class="tooltip_color rexsv-color-picker">
                                </div>

                                <div class="rexvs-form-group">
                                    <span class="label"><?php
	                                    _e( 'Tooltip Background Color: ', 'rexvs' ); ?></span>
									<?php
									$rexvs_tooltip_bg_color = '';
									if ( isset( $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ] ) ) {
										$rexvs_tooltip_bg_color = $rexvs_setup_data[ 'rexvs_tooltip_bg_color' ];
									}
									?>
                                    <input type="text" name="rexvs_tooltip_bg_color" value="<?php
									echo $rexvs_tooltip_bg_color; ?>" class="tooltip_bg_color rexsv-color-picker">
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/rexvs-box-->

                    <div class="rexvs-box hover-style">
                        <div class="rexvs-divider-style">
                            <h2><?php
								_e( 'Swatches Hover Style', 'rexvs' ); ?></h2>
                        </div>

                        <div class="rexvs-form-group picker-bottom">
                            <span class="label"><?php
	                            _e( 'Background Color:', 'rexvs' ); ?> </span>
							<?php
							$rexvs_hvr_swatches_bg_color = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ] ) ) {
								$rexvs_hvr_swatches_bg_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_bg_color' ];
							}
							?>
                            <input type="text" name="rexvs_hvr_swatches_bg_color" value="<?php
							echo $rexvs_hvr_swatches_bg_color; ?>" class="rexsv-color-picker">
                        </div>

                        <div class="rexvs-form-group picker-bottom">
                            <span class="label"><?php
	                            _e( 'Color:', 'rexvs' ); ?></span>
							<?php
							$rexvs_hvr_swatches_color = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ] ) ) {
								$rexvs_hvr_swatches_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_color' ];
							}
							?>
                            <input type="text" name="rexvs_hvr_swatches_color" value="<?php
							echo $rexvs_hvr_swatches_color; ?>" class="rexsv-color-picker">
                        </div>

                        <div class="enabled-swatches-border enabled-global-swatches-border">
                            <div class="rexvs-form-group">
                                <span class="label"><?php
	                                _e( 'Border width:', 'rexvs' ); ?></span>
                                <div class="border-style">
									<?php
									$rexvs_hvr_swatches_border_size = 1;

									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] ) && $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ] != '' ) {
										$rexvs_hvr_swatches_border_size = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_size' ];
									}
									?>
                                    <input type="number" name="rexvs_hvr_swatches_border_size" value="<?php
									echo $rexvs_hvr_swatches_border_size; ?>" min="0">
                                    <span class="hints"><?php
										_e( 'px', 'rexvs' ); ?></span>
                                </div>
                            </div>

                            <div class="rexvs-form-group">
                                <span class="label"><?php
	                                _e( 'Border color:', 'rexvs' ); ?></span>
                                <div class="border-style">
									<?php
									$rexvs_hvr_swatches_border_color = '';

									if ( isset( $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ] ) ) {
										$rexvs_hvr_swatches_border_color = $rexvs_setup_data[ 'rexvs_hvr_swatches_border_color' ];
									}
									?>
                                    <input type="text" name="rexvs_hvr_swatches_border_color" value="<?php
									echo $rexvs_hvr_swatches_border_color; ?>" class="rexsv-color-picker">
                                </div>
                            </div>
                        </div>
                        <!--/enabled-swatches-border-->

                    </div>
                    <!--/rexvs-box-->

                    <div class="rexvs-box selected-style">
                        <div class="rexvs-divider-style">
                            <h2><?php
								_e( 'Swatches Selected Style', 'rexvs' ); ?></h2>
                        </div>

                        <div class="rexvs-form-group">
                            <span class="label"><?php
	                            _e( 'Background Color:', 'rexvs' ); ?> </span>
							<?php
							$rexvs_seltd_swatches_bg_color = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ] ) ) {
								$rexvs_seltd_swatches_bg_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_bg_color' ];
							}
							?>
                            <input type="text" name="rexvs_seltd_swatches_bg_color" value="<?php
							echo $rexvs_seltd_swatches_bg_color; ?>" class="rexsv-color-picker">
                        </div>

                        <div class="rexvs-form-group">
                            <span class="label"><?php
	                            _e( 'Color:', 'rexvs' ); ?></span>
							<?php
							$rexvs_seltd_swatches_color = '';
							if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ] ) ) {
								$rexvs_seltd_swatches_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_color' ];
							}
							?>
                            <input type="text" name="rexvs_seltd_swatches_color" value="<?php
							echo $rexvs_seltd_swatches_color; ?>" class="rexsv-color-picker">
                        </div>

                        <div class="enabled-swatches-border enabled-global-swatches-border">
                            <div class="rexvs-form-group">
                                <span class="label"><?php
	                                _e( 'Border width:', 'rexvs' ); ?></span>
                                <div class="border-style">
									<?php
									$rexvs_seltd_swatches_border_size = 1;

									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] ) && $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ] != '' ) {
										$rexvs_seltd_swatches_border_size = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_size' ];
									}
									?>
                                    <input type="number" name="rexvs_seltd_swatches_border_size" value="<?php
									echo $rexvs_seltd_swatches_border_size; ?>" min="0">
                                    <span class="hints"><?php
										_e( 'px', 'rexvs' ); ?></span>
                                </div>
                            </div>

                            <div class="rexvs-form-group">
                                <span class="label"><?php
	                                _e( 'Border color:', 'rexvs' ); ?></span>
                                <div class="border-style">
									<?php
									$rexvs_seltd_swatches_border_color = '';

									if ( isset( $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ] ) ) {
										$rexvs_seltd_swatches_border_color = $rexvs_setup_data[ 'rexvs_seltd_swatches_border_color' ];
									}
									?>
                                    <input type="text" name="rexvs_seltd_swatches_border_color" value="<?php
									echo $rexvs_seltd_swatches_border_color; ?>" class="rexsv-color-picker">
                                </div>
                            </div>
                        </div>
                        <!--/enabled-swatches-border-->
                    </div>

                </div>
            </div>
            <!--/general tab content-->

            <div id="controls" class="controls">
                <div class="tab-content-header">
                    <h4><?php
						_e( 'Controls', 'rexvs' ); ?></h4>
                </div>

                <div class="tab-content-wrapper">
                    <div class="rexvs-box">

                        <div class="rexvs-form-group">
                            <span class="label" style="width: 250px"><?php
	                            _e( 'Dropdowns to Button Swatch:', 'rexvs' ); ?> </span>
                            <div class="rexvs-switcher">
								<?php
								if ( isset( $rexvs_setup_data[ 'rexvs_default_dropdown_to_button' ] ) && $rexvs_setup_data[ 'rexvs_default_dropdown_to_button' ] == 'on' ) {
									?>
                                    <input class="switch" id="rexvs_default_dropdown_to_button"
                                           name="rexvs_default_dropdown_to_button" type="checkbox" checked>
									<?php
								}
								else {
									?>
                                    <input class="switch" id="rexvs_default_dropdown_to_button"
                                           name="rexvs_default_dropdown_to_button" type="checkbox">
									<?php
								}
								?>
                                <label for="rexvs_default_dropdown_to_button"></label>
                            </div>
                            <span class="hints"><?php
								_e( 'Auto Convert Dropdowns to Button Swatch (only for "select" attribute type) by Default', 'rexvs' ); ?></span>
                        </div>

                        <div class="rexvs-form-group">
                            <span class="label" style="width: 250px"><?php
	                            _e( 'Disable default plugin stylesheet:', 'rexvs' ); ?></span>
                            <div class="rexvs-switcher">
			                    <?php
			                    if ( isset( $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] ) && $rexvs_setup_data[ 'rexvs_disable_stylesheet' ] == 'on' ) {
				                    ?>
                                    <input class="switch" id="rexvs_disable_stylesheet" name="rexvs_disable_stylesheet"
                                           type="checkbox" checked>
				                    <?php
			                    }
			                    else {
				                    ?>
                                    <input class="switch" id="rexvs_disable_stylesheet" name="rexvs_disable_stylesheet"
                                           type="checkbox">
				                    <?php
			                    }
			                    ?>
                                <label for="rexvs_disable_stylesheet"></label>
                            </div>
                            <span class="hints"><?php
			                    _e( 'Option to enable/disable default plugin stylesheet for theme developer', 'rexvs' ); ?></span>
                        </div>

                        <div class="rexvs-form-group">
                            <span class="label" style="width: 250px"><?php
	                            _e( 'Delete data on plugin uninstall:', 'rexvs' ); ?></span>
                            <div class="rexvs-switcher">
								<?php
								if ( isset( $rexvs_setup_data[ 'rexvs_delete_data' ] ) && $rexvs_setup_data[ 'rexvs_delete_data' ] == 'on' ) {
									?>
                                    <input class="switch" id="rexvs_delete_data" name="rexvs_delete_data"
                                           type="checkbox" checked>
									<?php
								}
								else {
									?>
                                    <input class="switch" id="rexvs_delete_data" name="rexvs_delete_data"
                                           type="checkbox">
									<?php
								}
								?>
                                <label for="rexvs_delete_data"></label>
                            </div>
                            <span class="hints"><?php
								_e( 'Delete all plugin data on plugin uninstall.', 'rexvs' ); ?></span>
                        </div>

                    </div>
                </div>
            </div>
            <!--/controls tab content-->

            <div id="gopro" class="gopro" style="">
                <div class="tab-content-wrapper gopro-content-wrapper">
                    <div class="rexvs-box">
                        <div class="content-header">
                            <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/icon/document.png'?>"
                                 class="title-icon" alt="documentation">
                            <h4><?php _e('Documentation', 'rexvs'); ?></h4>
                        </div>
                        <div class="content-body">
                            <p>
	                            <?php
                                _e(
                                    'Get started by spending some time with the documentation and generate flawless product
                                feed for major online marketplaces within minutes.',
                                    'rexvs'
                                ); ?>
                            </p>

                            <a class="btn-default" href="https://rextheme.com/docs-category/variation-swatches/"
                               target="_blank"><?php _e('Documentation', 'rexvs'); ?></a>
                        </div>
                    </div>
                    <div class="rexvs-box">
                        <div class="content-header">
                            <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/icon/support.png'?>"
<<<<<<< HEAD
                                 class="title-icon" alt="documentation">
                            <h4>Support</h4>
=======
                                 class="title-icon" alt="support">
                            <h4><?php _e('Support', 'rexvs'); ?></h4>
>>>>>>> a4570684465af7d485f7c104859bf565d11e44b1
                        </div>
                        <div class="content-body">
                            <p>
	                            <?php
                                _e(
                                    'Can’t find solution with our documentation? Just post a ticket. Our professional team is here to solve your problems.',
                                    'rexvs'
                                );
                                ?>
                            </p>

                            <a class="btn-default" href="https://wordpress.org/support/plugin/rex-variation-swatches-for-woocommerce/#new-topic-0"
                               target="_blank"><?php _e('Post A Ticket', 'rexvs'); ?></a>
                        </div>
                    </div>
                    <div class="rexvs-box">
                        <div class="content-header">
                            <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/icon/rating.png'?>"
                                 class="title-icon" alt="rating">
                            <h4><?php _e('Show Your Love', 'rexvs'); ?></h4>
                        </div>
                        <div class="content-body">
                            <p>
                                <?php
                                _e(
                                    'We love to have you in Variation Swatches for WooCommerce family. Take your 2 minutes to review and speed the love to encourage us to keep it going.',
                                    'rexvs'
                                ); ?>
                            </p>

                            <a class="btn-default" href="https://wordpress.org/support/plugin/rex-variation-swatches-for-woocommerce/reviews/#new-post"
                               target="_blank"><?php _e('Leave A Review', 'rexvs'); ?></a>
                        </div>
                    </div>
                    <div class="rexvs-box">
                        <div class="upgrade-pro">
                            <img src="<?php echo plugin_dir_url(__FILE__) . '../assets/icon/logo.png'?>" alt="logo" class="img-fluid">

                            <a class="btn-default" href="https://rextheme.com/variation-swatches-for-woocommerce/" target="_blank"><?php _e('Upgrade to Pro', 'rexvs'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--Go Pro tab content-->

            <div class="button-area">
                <button type="button" class="btn-default save-settings" id="rexvs_settings_submit">
					<?php
					_e( 'Save Settings', 'rexvs' ); ?>
                    <svg id="rexsv-spinner" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sync-alt"
                         class="svg-inline--fa fa-sync-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 512 512">
                        <path fill="currentColor"
                              d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"></path>
                    </svg>
                </button>
                <p class="validation-msg" id="rexvs_settings_status" style="display:none;"></p>
            </div>

        </div>
    </div>
</div>

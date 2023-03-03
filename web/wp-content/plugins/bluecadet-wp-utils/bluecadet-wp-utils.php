<?php

/**
 * @package BluecadetUtils
 *
 * @wordpress-plugin
 * Plugin Name:       Bluecadet Utils
 * Description:       Various utilities for Bluecadet projects.
 * Version:           1.0.0
 * Author:            Bluecadet
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bluecadet-utils-activator.php
 */
function activate_bluecadet_utils() {

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bluecadet-utils-deactivator.php
 */
function deactivate_bluecadet_utils() {

}

register_activation_hook( __FILE__, 'activate_bluecadet_utils' );
register_deactivation_hook( __FILE__, 'deactivate_bluecadet_utils' );

/**
 * Include Util Files
 *
 */
include( plugin_dir_path( __FILE__ ) . 'lib/src/LabelMaker.php');
include( plugin_dir_path( __FILE__ ) . 'lib/src/PostTypes.php');
include( plugin_dir_path( __FILE__ ) . 'lib/src/Taxonomies.php');
// include( plugin_dir_path( __FILE__ ) . 'lib/src/GoogleAnalytics.php');
include( plugin_dir_path( __FILE__ ) . 'lib/src/TimberHelpers.php');
<?php
/**
 * Child Theme Functions
 *
 * Functions or examples that may be used in a child them. Don't for get to edit them, to get them working.
 *
 * @link https://make.wordpress.org/core/handbook/inline-documentation-standards/php-documentation-standards/#6-file-headers
 * @since 20150814.1
 *
 * @category            WordPress_Theme
 * @package             Extra_AllNL_Child_Theme
 * @subpackage          theme_functions
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROEXALLNL_VERSION', '20160512.1' );
define( 'ROEXALLNL_CDIR', get_stylesheet_directory() ); // if child, will be the file path, with out backslash
define( 'ROEXALLNL_CURI', get_stylesheet_uri() ); // URL to the theme directory, not back slash

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );


/**
 * Add custom style sheet to the HTML Editor
 **/
function ro_theme_add_editor_styles() {
	if ( file_exists( get_stylesheet_directory() . "/editor-style.css" ) ) {
		add_editor_style( 'editor-style.css' );
	}
}

add_action( 'init', 'ro_theme_add_editor_styles' );


/**
 * Setup Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
/*
function ro_theme_setup() {
	load_child_theme_textdomain( 'ro-theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'ro_theme_setup' );
*/

/**
 * Load a custom.css style sheet, if it exists in a child theme.
 *
 * @return void
 */
function ro_enqueue_custom_stylesheets() {
	if ( ! is_admin() ) {
		if ( is_child_theme() ) {
			if ( file_exists( get_stylesheet_directory() . "/custom.css" ) ) {
				wp_enqueue_style( 'ro-theme-custom-css', get_template_directory_uri() . '/custom.css' );
			}
		}
	}
}

//add_action( 'wp_enqueue_scripts', 'ro_enqueue_custom_stylesheets', 11 );
/**
 * The gallery module not recognise the image orientation.
 * All images reduced to the fixed sizes and may be cropped.
 * We can change those fixed sizes. Please add the following
 * code to the functions.php :
 *
 * @link ET Forums: https://www.elegantthemes.com/forum/viewtopic.php?f=187&t=470086&p=2610589&hilit=image+sizes+gallery+image+cropped#p2610589
 *
 * @param $height
 *
 * @return string
 */
function gallery_size_h( $height ) {
	return '1280';
}

add_filter( 'et_pb_gallery_image_height', 'gallery_size_h' );
function gallery_size_w( $width ) {
	return '9999';
}

add_filter( 'et_pb_gallery_image_width', 'gallery_size_w' );
?>
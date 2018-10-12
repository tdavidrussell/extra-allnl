<?php
/**
 * Child Theme Functions
 *
 * Functions or examples that may be used in a child them. Don't for get to edit them, to get them working.
 *
 * @link                https://make.wordpress.org/core/handbook/inline-documentation-standards/php-documentation-standards/#6-file-headers
 * @since               20150814.1
 *
 * @category            WordPress_Theme
 * @package             Extra_AllNL_Child_Theme
 * @subpackage          theme_functions
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROEXALLNL_VERSION', '20181012.1' );
define( 'ROEXALLNL_CDIR', get_stylesheet_directory() ); // if child, will be the file path, with out backslash
define( 'ROEXALLNL_CURI', get_stylesheet_uri() ); // URL to the theme directory, not back slash


/**
 * By default WordPress adds all sorts of code between the opening and closing head tags of a WordPress theme
 * So lets clean out some of them
 *
 */
function ro_remove_head_links() {

	/** remove some header information  **/
	remove_action( 'wp_head', 'feed_links_extra', 3 );  //category feeds
	remove_action( 'wp_head', 'feed_links', 2 );        //post and comments feed, see ro_enqueue_default_feed_link()
	remove_action( 'wp_head', 'rsd_link' );              //only required if you are looking to blog using an external tool
	remove_action( 'wp_head', 'wlwmanifest_link' );      //something to do with windows live writer
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 ); //next previous post links
	remove_action( 'wp_head', 'wp_generator' );          //generator tag ie WordPress version info
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );  //short links like ?p=124


}

add_action( 'init', 'ro_remove_head_links' );

/**
 * above we remove all feed (RSS) links lets put them back, for post content.
 * in ro_remove_head_links() we had to remove the post and comments rss link
 * now we want to add rss back just for post content
 * because feed_links() adds both the comments and posts feeds
 *
 * @see ro_remove_head_links()
 */
function ro_enqueue_default_feed_link() {
	echo "<link rel='alternate' type='application/rss+xml' title='" . get_bloginfo( 'name' ) . " &raquo; Feed' href='" . get_feed_link() . "' />";
}

add_action( 'wp_head', 'ro_enqueue_default_feed_link' );


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

/**
 * this will change the auto height cropping of featured images
 */
function ro_disable_cropping_on_featured_images() {
	add_image_size( 'extra-image-single-post', 1280, 1000, true );
}

//add_action('after_setup_theme', 'ro_disable_cropping_on_featured_images', 11);

/**
 * https://premium.wpmudev.org/blog/how-to-extend-the-auto-logout-period-in-wordpress/
 */
add_filter( 'auth_cookie_expiration', 'keep_me_logged_in_for_1_year' );

function keep_me_logged_in_for_1_year( $expirein ) {
	//return 604800; // 1 week in seconds
	return 31556926; // 1 year in seconds
}


/*
 * Will add divi to the resources post type
*/

function sj_et_builder_post_types( $post_types ) {
	$post_types[] = 'resources';

	//$post_types[] = 'ANOTHER_CPT_HERE';

	return $post_types;
}

add_filter( 'et_builder_post_types', 'sj_et_builder_post_types' );

/* change the image quality on resizing .... */
add_filter( 'jpeg_quality', function ( $arg ) {
	return 100;
} );
/**
 * for the RO Resources Plugin
 */
if ( ! function_exists( 'et_pb_resources_meta_box' ) ) :
	function et_pb_resources_meta_box() {
		global $post;

		$cpt_post_cf = get_post_custom_values( "rone_resource_link_url" );
		?>

		<div class="et_project_meta">

			<span class="published"><?php echo esc_html__( 'Posted on', 'Divi' ); ?><?php echo get_the_date(); ?> </span> |

			<?php echo get_the_term_list( get_the_ID(), 'resource-categories', '', ', ' ); ?>
			<!--
			<span class="published"><?php echo esc_html__( 'Visit', 'Divi' ); ?>: <a href="<?php echo get_metadata( "post", $post->ID, "rone_resource_link_url", true ); ?>" target="_blank" > <?php echo $post->ID;
			the_title(); ?></a></span>
			-->
			<p>
				<span class="published">
					<?php echo esc_html__( 'Visit', 'Divi' ); ?>: <a href="<?php echo $cpt_post_cf[0] ?>" target="_blank"> <?php the_title(); ?></a>
				</span>
			</p>


		</div>
	<?php }
endif;


/** Removes the WordPress logo on the user side. */
add_action( 'admin_bar_menu', 'ro_remove_wp_logo', 9999 );

function ro_remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
	//$wp_admin_bar->remove_node( 'et-use-visual-builder' );
}

?>
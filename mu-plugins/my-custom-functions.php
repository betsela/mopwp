<?php
   /*
   Plugin Name: RadiateWP Custom Functions Plugin
   Plugin URI: https://radiatewp.com
   Description: A set of custom functions based on the course by Nathan Ingram in Dec 2021
   Version: 1.0
   Author: Carol Stambaugh
   Author URI: https://radiatewp.com
   License: GPL2
   */
   
   /////////////////// WORDPRESS CORE RELATED FUNCTIONS /////////////////// 
   
   // Disable Post Formats

add_action ('after_setup_theme','rwp_no_mo_po_fo',100);
function rwp_no_mo_po_fo () {
	remove_theme_support( 'post-formats' );
}

// Disable Emojis

function rwp_disable_emojis() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

 add_filter( 'tiny_mce_plugins', 'rwp_disable_emojis_tinymce' );
 add_filter( 'wp_resource_hints', 'rwp_disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'rwp_disable_emojis' );

function rwp_disable_emojis_tinymce( $plugins ) {
 if ( is_array( $plugins ) ) {
 return array_diff( $plugins, array( 'wpemoji' ) );
 } else {
 return array();
 }
}

function rwp_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
 if ( 'dns-prefetch' == $relation_type ) {

 $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

$urls = array_diff( $urls, array( $emoji_svg_url ) );
 }

return $urls;
}

// Disables feeds

function rwp_clean_feeds() {

// Redirects all feeds to home page.
	$url = site_url();
	wp_redirect( $url );
}

add_action( 'do_feed', 'rwp_clean_feeds', 1 );
add_action( 'do_feed_rdf',  'rwp_clean_feeds', 1 );
add_action( 'do_feed_rss',  'rwp_clean_feeds', 1 );
add_action( 'do_feed_rss2', 'rwp_clean_feeds', 1 );
add_action( 'do_feed_atom', 'rwp_clean_feeds', 1 );
add_action( 'do_feed_rss2_comments', 'rwp_clean_feeds', 1 );
add_action( 'do_feed_atom_comments', 'rwp_clean_feeds', 1 );

// Clean Up WP_Head

remove_action( 'wp_head', 'wp_generator' );                           // Removes WordPress version.
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );            // Removes shortlink.
remove_action( 'wp_head', 'rsd_link' );                               // Removes Really Simple Discovery link.
remove_action( 'wp_head', 'feed_links', 2 );                          // Removes RSS feed links.
remove_action( 'wp_head', 'feed_links_extra', 3 );                    // Removes all extra RSS feed links.
remove_action( 'wp_head', 'wlwmanifest_link' );                       // Removes wlwmanifest.xml.
remove_action( 'wp_head', 'wp_resource_hints', 2 );                   // Removes meta rel=dns-prefetch href=//s.w.org
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); // Removes relational links for the posts.
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );      // Removes oEmbeds.


// Disable Post by Email at Settings > Writing

add_filter( 'enable_post_by_email_configuration', '__return_false', 100 );

///////////////////  UNSET CORE IMAGE SIZES /////////////////// 

add_filter( 'intermediate_image_sizes_advanced', 'rwp_remove_default_image_sizes' );
// Source quadlayers.com/remove-wordpress-default-image-sizes/
function rwp_remove_default_image_sizes( $sizes ) {
  unset($sizes['medium_large']); // disable medium-large size
  unset($sizes['1536x1536']);    // disable 2x medium-large size
  unset($sizes['2048x2048']);    // disable 2x large size
 return $sizes;
}

///////////////////  WP-ADMIN CUSTOMIZATIONS /////////////////// 

// Replace Howdy

function rwp_replace_howdy( $wp_admin_bar ) {
    $my_account=$wp_admin_bar->get_node('my-account');
    $newtitle = str_replace( 'Howdy,', 'Greetings,', $my_account->title );
    $wp_admin_bar->add_node( array(
        'id' => 'my-account',
        'title' => $newtitle,
    ) );
}
add_filter( 'admin_bar_menu', 'rwp_replace_howdy',25 );

// Remove WP Dashboard Menu
function rwp_admin_bar_remove() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'rwp_admin_bar_remove', 0);

// Modify the thank you footer text

add_filter('admin_footer_text', 'rwp_modify_footer_admin');

function rwp_modify_footer_admin () {
	echo '<div style="float:left;margin-right:8px;""><img src="https://radiatewp.com/wp-content/uploads/2021/12/radiateWP-site-icon.png" width="40px"></div><div style="height:8px;">&nbsp;</div><span><a href="https://radiatewp.com" target="_blank" style="color:#1e91af;text-decoration:none;font-weight:bold;">RadiateWP</a> &ndash; WordPress Maintenance and Support</span>';
}

// Remove Dashboard Widgets
function rwp_remove_dashboard_widgets() {
global $wp_meta_boxes;
// At a Glance Widget
unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
// Quick Draft Widget
unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
// News Widget
unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']) ;
//Site Health
unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
//Events Calendar
unset( $wp_meta_boxes['dashboard']['normal']['core']['tribe_dashboard_widget'] );
//WooSetup
unset( $wp_meta_boxes['dashboard']['normal']['high']['wc_admin_dashboard_setup'] );
//Welcome Panel
remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action('wp_dashboard_setup', 'rwp_remove_dashboard_widgets',11);

// Disable Site Health Admin Menu
function rwp_remove_site_health_menu() {
    remove_submenu_page( 'tools.php', 'site-health.php' );
}
add_action( 'admin_menu', 'rwp_remove_site_health_menu' );

/////////////////// CREATE CUSTOM DASHBOARD WIDGETS /////////////////// 


// Create Custom Client Dashboard Widget

add_action('wp_dashboard_setup', 'rwp_custom_dashboard_widget');
 
function rwp_custom_dashboard_widget() {
global $wp_meta_boxes;

wp_add_dashboard_widget('rwp_client_widget', '&nbsp;', 'rwp_client_widget_content');
}

function rwp_client_widget_content() {
$url = get_site_url();
echo '<p style="text-align:center"><img src="https://radiatewp.com/wp-content/uploads/2020/06/RadiateWP_400x400.png" /></p>
<p style="margin: 1.5em 0;"><a style="min-width:45%; text-align:center;float:left;margin:5px;" class="button" href="https://radiatewp.helpscoutdocs.com/" target="_blank" rel="noopener noreferrer">Documentation and FAQs</a></p>
<p style="margin: 1.5em 0;"><a style="min-width:45%; text-align:center;float:left;margin:5px;" class="button" href="https://radiatewp.com/purchase-support/" target="_blank" rel="noopener noreferrer">Purchase Support Block</a></p>
<p style="margin: 1.5em 0;"><a style="min-width:45%; text-align:center;float:left;margin:5px;" class="button" href="https://radiatewp.com/blog/" target="_blank" rel="noopener noreferrer">RadiateWP Blog</a></p>
<p style="margin: 1.5em 0;"><a style="min-width:45%; text-align:center;float:left;margin:5px;" class="button" href="https://clients.radiatewp.com/support-request/" target="_blank" rel="noopener noreferrer">Create a Support Ticket</a></p>
<p style="margin: 1.5em 0;"><a style="min-width:45%; text-align:center;float:left;margin:5px;" class="button" href="https://radiatewp.com/" target="_blank" rel="noopener noreferrer">RadiateWP Website</a></p>
<p style="margin: 1.5em 0;"><a style="min-width:45%; text-align:center;float:left;margin:5px;" class="button" href="https://radiateforgood.com" target="_blank" rel="noopener noreferrer">Radiate For Good</a></p>
<div style="clear:both;float:none;"></div>';
}

//Add a Support Form Widget

function rwp_register_custom_dashboard_support_widget() {
	wp_add_dashboard_widget(
		'custom_dashboard_widget',
		'Support Request Form', //Title for Dashboard Widget
		'rwp_custom_dashboard_support_widget_content'
	);
}
function rwp_custom_dashboard_support_widget_content() {
    echo do_shortcode('[gravityform id="2" title="false" description="false" ajax="true"]'); //Add your shortcode here
}
add_action( 'wp_dashboard_setup', 'rwp_register_custom_dashboard_support_widget' );

/////////////////// SHORTCODE CREATION /////////////////// 

//Anti-Spam Email Shortcode
//Use this shortcode [email]nathan@ithemes.com[/email]

function rwp_protect_email_address( $atts , $content=null ) {
    for ($i = 0; $i < strlen($content); $i++) $encodedmail .= "&#" . ord($content[$i]) . ';';
    return '<a href="mailto:'.$encodedmail.'">'.$encodedmail.'</a>';
}
add_shortcode('email', 'rwp_protect_email_address');

/////////////////// MISCELLANEOUS FUNCTIONS /////////////////// 


// Define Custom Image Sizes

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'News Grid', 300, 200, true );
	add_image_size( 'News Large', 800, 400, true );
}

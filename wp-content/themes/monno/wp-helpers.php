<?php

#remove wordpress version number from head & rss feed
function wpbeginner_remove_version() {
	return '';
}
add_filter('the_generator', 'wpbeginner_remove_version');

#meta wordpress.org link from site info widget
add_filter( 'widget_meta_poweredby', '__return_empty_string' );

#remove admin wordpress logo
add_action( 'wp_before_admin_bar_render', function() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}, 7 );

#remove thank you message from admin panel footer
function remove_footer_admin () 
{
	echo '<span id="footer-thankyou">Developed by <a href="http://inspire32.com/" target="_blank">Inspire32</a>	</span>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

##disable update notice for all users except admin users
function hide_update_notice_to_all_but_admin_users()
{
	if (!current_user_can('update_core')) {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
}
add_action( 'admin_head', 'hide_update_notice_to_all_but_admin_users', 1 );

#Pick out the version number from scripts and styles
function remove_version_from_style_js( $src ) 
{
	if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
	$src = remove_query_arg( 'ver', $src );
	return $src;
}
add_filter( 'style_loader_src', 'remove_version_from_style_js');
add_filter( 'script_loader_src', 'remove_version_from_style_js');

#change howdy text in admin panel top right corner
add_filter('gettext', 'change_howdy', 10, 3);
function change_howdy($translated, $text, $domain) 
{
	if (!is_admin() || 'default' != $domain)
	return $translated;

	if (false !== strpos($translated, 'Howdy'))
	return str_replace('Howdy', 'Welcome', $translated);

	return $translated;
}

#restrict imae size in media
function media_max_image_size( $file ) 
{
	$size = $file['size'];
	$size = $size / 1024;
	$type = $file['type'];
	$is_image = strpos( $type, 'image' ) !== false;
	$limit = 50;
	$limit_output = '50kb';
	$dimensions = getimagesize($file['tmp_name']);
	$width= $dimensions[0];
	$height = $dimensions[1];
	
	if ( $is_image && $size > $limit ) {
		$file['error'] = 'Image files must be smaller than ' . $limit_output;
	}elseif($width > 980 || $height > 980){
		$file['error'] = 'Allowed dimensions: maximum width 980px and maximum height 980px';
	}

	return $file;

}
//add_filter( 'wp_handle_upload_prefilter', 'media_max_image_size' );

#Limit posts revisions. place in wp-config.php file
define( 'WP_POST_REVISIONS', 3 );

#Remove the Word 'Archive' from Category & Tag title
add_filter( 'get_the_archive_title', function ($title) 
{
	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>' ;
	}

	return $title;
});

#Remove the word 'Archives:' in front of CPT archive pages
add_filter( 'get_the_archive_title', 'archive_title_remove_prefix' );
function archive_title_remove_prefix( $title ) {
	if ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	}
	
	return $title;
	
}

#Include specific post types in search results
function exclude_pages_from_search($query) {
	if ( $query->is_main_query() && is_search() ) {
		$query->set( 'post_type', ['post','service'] );
	}
	return $query;
}
add_filter( 'pre_get_posts','exclude_pages_from_search' );

#disable screen options in admin panel
function wpb_remove_screen_options() { 
	if(!current_user_can('manage_options')) {
		return false;
	}
	return true; 
}
add_filter('screen_options_show_screen', 'wpb_remove_screen_options');

#remove help tab in admin panel
add_filter( 'contextual_help', 'mytheme_remove_help_tabs', 999, 3 );
function mytheme_remove_help_tabs($old_help, $screen_id, $screen){
	if(!current_user_can('manage_options')) {
		$screen->remove_help_tabs();
	}
	return $old_help;
}
<?php
/**
 * Plugin shortcode
 *
 * @package     Wow_Plugin
 * @subpackage  Public/Shortcode
 * @author      Dmytro Lobov <i@lobov.dev>
 * @copyright   2019 Wow-Company
 * @license     GNU Public License
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

extract( shortcode_atts( array( 'id' => "" ), $atts ) );

global $wpdb;
$table  = $wpdb->prefix . 'wow_' . $this->plugin['prefix'];
$sSQL   = $wpdb->prepare( "select * from $table WHERE id = %d", $id );
$result = $wpdb->get_results( $sSQL );

if ( count( $result ) > 0 ) {

	foreach ( $result as $key => $val ) {
		$param = unserialize( $val->param );

			ob_start();
			include( 'partials/public.php' );
			ob_end_clean();

			$time = ! empty( $param['time'] ) ? $param['time'] : '';

			$slug    = $this->plugin['slug'];
			$version = $this->plugin['version'];

			$url_style = $this->plugin['url'] . 'assets/css/style.min.css';
			wp_enqueue_style( $slug, $url_style, null, $version );

			$inline_style = self::style($param, $id);
			wp_add_inline_style( $slug, $inline_style );


			if ( empty( $param['disable_fontawesome'] ) ) {
				$url_icons = $this->plugin['url'] . 'assets/vendors/fontawesome/css/fontawesome-all.min.css';
				wp_enqueue_style( $slug . '-fontawesome', $url_icons, null, '5.6.3' );
			}

	}

}

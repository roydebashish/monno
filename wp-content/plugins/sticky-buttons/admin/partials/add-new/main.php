<?php
/**
 * Main Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once( 'settings/main.php' );

?>
<fieldset class="itembox">
	<legend>
	  <?php esc_attr_e( 'Main', $this->plugin['text'] ); ?>
	</legend>

	<div class="container">
		<div class="element">
		<?php esc_attr_e( 'Position', $this->plugin['text'] ); ?><?php echo self::tooltip( $position_help ); ?> <?php echo self::pro(); ?>
		<?php echo self::option( $position ); ?>
		</div>
		<div class="element">
		<?php esc_attr_e( 'Shape', $this->plugin['text'] ); ?><?php echo self::tooltip( $shape_help ); ?><br/>
		<?php echo self::option( $shape ); ?>
		</div>
		<div class="element">
		<?php esc_attr_e( 'Size', $this->plugin['text'] ); ?><?php echo self::tooltip( $size_help ); ?> <?php echo self::pro(); ?>
		<?php echo self::option( $size ); ?>
		</div>
	</div>

	<div class="container">
		<div class="element">
		<?php esc_attr_e( 'Space', $this->plugin['text'] ); ?><?php echo self::tooltip( $space_help ); ?><br/>
		<?php echo self::option( $space ); ?>
		</div>
		<div class="element">
		<?php esc_attr_e( 'Animation', $this->plugin['text'] ); ?><?php echo self::tooltip( $animation_help ); ?> <?php echo self::pro(); ?>
		<?php echo self::option( $animation ); ?>
		</div>
		<div class="element">
		<?php esc_attr_e( 'Shadow', $this->plugin['text'] ); ?><?php echo self::tooltip( $shadow_help ); ?> <?php echo self::pro(); ?>
		<?php echo self::option( $shadow ); ?>
		</div>
	</div>

	<div class="container">
		<div class="element">
		<?php esc_attr_e( 'Z-index', $this->plugin['text'] ); ?><?php echo self::tooltip( $zindex_help ); ?><br/>
		<?php echo self::option( $zindex ); ?>
		</div>
		<div class="element">
		</div>
		<div class="element">
		</div>
	</div>

</fieldset>
<?php
/**
 * Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( 'settings/menu.php' );
?>

<div class="adding-menu-1">
	<?php if ( $count_i > 0 ) {
		for ( $i = 0; $i < $count_i; $i ++ ) { ?>
					<fieldset class="itembox">
						<legend>
				<?php esc_attr_e( 'Item ', $this->plugin['text'] ); ?><?php echo esc_attr( $i + 1 ); ?>
						</legend>
						<div class="control">
							<span class="dashicons dashicons-move"></span>
							<span class="dashicons dashicons-minus toogle"></span>
							<span class="dashicons dashicons-plus toogle"></span>
							<span class="dashicons dashicons-no-alt item-del"></span>
						</div>
						<div class="menu_block">
							<div class="container">
								<div class="element">
					<?php esc_attr_e( 'Icon', $this->plugin['text'] ); ?>:
					<?php esc_attr_e( 'custom', $this->plugin['text'] ); ?>
					<?php echo self::tooltip( $item_icon_help ); ?> <?php echo self::pro(); ?>
									<br/>
					<?php echo self::option( $item_icon_[ $i ] ); ?>
								</div>
								<div class="element">
					<?php esc_attr_e( 'Label Text', $this->plugin['text'] ); ?>
					<?php echo self::tooltip( $item_tooltip_help ); ?>
									<br/>
					<?php echo self::option( $item_tooltip_[ $i ] ); ?>
								</div>
								<div class="element">
              <span class="sub_menu">
              </span>
								</div>
							</div>
							<div class="container">
								<div class="element">
					<?php esc_attr_e( 'Item type', $this->plugin['text'] ); ?>
					<?php echo self::tooltip( $item_type_help ); ?> <?php echo self::pro(); ?>
					<?php echo self::option( $item_type_[ $i ] ); ?>
								</div>
								<div class="element type-param">
									<div class="type-link">
										<span class="type-link-text">Link</span>
										<br/>
					  <?php echo self::option( $item_link_[ $i ] ); ?>
									</div>
								</div>
								<div class="element type-link-blank">
									<input type="checkbox" disabled>
					<?php esc_attr_e( 'Open in new window', $this->plugin['text'] ); ?> <?php echo self::pro(); ?>
								</div>
							</div>

							<div class="container">
								<div class="element">
					<?php esc_attr_e( 'Text Сolor', $this->plugin['text'] ); ?><br/>
					<?php echo self::option( $color_[ $i ] ); ?>
								</div>
								<div class="element">
					<?php esc_attr_e( 'Background Color', $this->plugin['text'] ); ?><br/>
					<?php echo self::option( $bcolor_[ $i ] ); ?>
								</div>
								<div class="element">
									<input type="checkbox" disabled>
					<?php esc_attr_e( 'Hold open', $this->plugin['text'] ); ?>
					<?php echo self::tooltip( $hold_open_help ); ?> <?php echo self::pro(); ?>
								</div>
							</div>

							<div class="container">

								<div class="element button_id">
					<?php esc_attr_e( 'ID for element', $this->plugin['text'] ); ?>
					<?php echo self::tooltip( $button_id_help ); ?>
									<br/>
					<?php echo self::option( $button_id_[ $i ] ); ?>
								</div>
								<div class="element button_class">
					<?php esc_attr_e( 'Class for element', $this->plugin['text'] ); ?>
					<?php echo self::tooltip( $button_class_help ); ?>
									<br/>
					<?php echo self::option( $button_class_[ $i ] ); ?>
								</div>
								<div class="element">
								</div>

							</div>
						</div>
					</fieldset>
			<?php
		}
	}
	?>
</div>

<div class="submit-bottom">
	<input type="button" value="<?php esc_attr_e( 'Add item', $this->plugin['text'] ); ?>" class="add-item"
				 onclick="itemadd(1)">
</div>

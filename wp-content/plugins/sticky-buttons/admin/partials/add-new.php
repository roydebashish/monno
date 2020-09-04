<?php
/**
 * Add new Element
 *
 * @package     Wow_Plugin
 * @subpackage  Admin/Add_Item
 * @author      Dmytro Lobov <i@lobov.dev>
 * @copyright   2019 Wow-Company
 * @license     GNU Public License
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// include the database params for item
include_once( 'include-data.php' );

// include the settings param
include_once( 'add-new/settings/add-new.php' );

$url_form = admin_url() . 'admin.php?page=' . $this->plugin['slug'];
?>
	<form action="<?php echo esc_url( $url_form ); ?>" method="post" name="post" class="wow-plugin" id="wow-plugin">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">

				<!--      Block with the Title and content-->
				<div id="post-body-content" style="position: relative;">

					<div id="titlediv">

						<div id="titlewrap">
							<label class="screen-reader-text" id="title-prompt-text" for="title">Enter title here</label>
							<input type="text" name="title" size="30" value="<?php echo esc_attr($title); ?>" id="title"
										 placeholder="<?php esc_attr_e( 'Register an item name', $this->plugin['text'] ); ?>">
						</div>

					</div>
				</div>

				<!--      Sidebar with the setting-->
				<div id="postbox-container-1" class="postbox-container">

			<?php include_once( 'add-new/targeting.php' ); // Include the targets ?>

					<div id="submitdiv" class="postbox ">
						<h2 class="hndle ui-sortable-handle">
							<span><?php esc_attr_e( 'Publish', $this->plugin['text'] ); ?>
								<?php echo self::tooltip($show_help);?> <?php echo self::pro();?>
							</span>
						</h2>

						<div class="inside">


							<div class="container">
								<div class="element">
					<?php echo self::option( $show ); ?>

								</div>
							</div>

							<div class="submitbox" id="submitpost">

								<div id="major-publishing-actions">

									<div id="delete-action">
									<?php if ( ! empty( $id ) ) {
										echo '<a class="submitdelete deletion" href="admin.php?page=' . $this->plugin['slug'] .
										     '&info=delete&did=' . $id . '">' . esc_attr( 'Delete', $this->plugin['text'] ) . '</a>';
									}; ?>
                </div>

									<div id="publishing-action">
										<span class="saving"><?php esc_attr_e( 'Saving', $this->plugin['text'] ); ?></span> <input
											name="submit" id="submit"
											class="button button-primary button-large"
											value="<?php esc_attr_e( $btn ); ?>" type="submit">
									</div>

									<div class="clear"></div>

								</div>
							</div>
						</div>
					</div>
				</div>

				<!--      Block for main settings pages-->
				<div id="postbox-container-2" class="postbox-container">
					<div id="postoptions" class="postbox ">
						<div class="inside">

							<div class="tab-box">
								<ul class="tab-nav">
					<?php
					$tab_menu = array(
						'main' => esc_attr__( 'Settings', $this->plugin['text'] ),
						'menu' => esc_attr__( 'Menu', $this->plugin['text'] ),
					);
					$i        = 1;
					foreach ( $tab_menu as $menu => $val ) {
						echo '<li><a href="#t' . $i . '">' . $val . '</a></li>';
						$i ++;
					}
					?>
								</ul>
								<div class="tab-panels">
								<?php
								$t = 1;
								foreach ( $tab_menu as $menu => $val ) {
									echo '<div id="t' . $t . '">';
									// includes the settings pages
									include_once( 'add-new/' . $menu . '.php' );
									echo '</div>';
									$t ++;
								}
								?>
              </div>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>

		<!--  main param for adding in database-->
		<input type="hidden" name="tool_id" value="<?php echo esc_attr( $tool_id ); ?>" id="tool_id"/>
		<input type="hidden" name="param[time]" value="<?php echo esc_attr( time() ); ?>"/>
		<input type="hidden" name="add" id="add_action" value="<?php echo esc_attr( $add_action ); ?>"/>
		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>"/>
		<input type="hidden" name="data" value="<?php echo esc_attr( $data ); ?>"/>
		<input type="hidden" name="page" value="<?php echo esc_attr( $this->plugin['slug'] ); ?>"/>
		<input type="hidden" name="prefix" value="<?php echo esc_attr( $this->plugin['prefix'] ); ?>" id="prefix"/>
	  <?php wp_nonce_field( $this->plugin['slug'] . '_action', $this->plugin['slug'] . '_nonce' ); ?>

	</form>

	<div id="clone" style="display: none;">
  <?php include_once('add-new/clone.php'); ?>
</div>
<?php

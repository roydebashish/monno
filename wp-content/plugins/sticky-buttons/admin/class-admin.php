<?php

/**
 * Admin Class
 *
 * @package     Wow_Plugin
 * @subpackage  Admin
 * @author      Dmytro Lobov <i@lobov.dev>
 * @copyright   2019 Wow-Company
 * @license     GNU Public License
 * @version     1.0
 */

namespace sticky_buttons_pro;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Wow_Plugin_Admin
 *
 * @package wow_plugin
 *
 * @property array plugin - base information about the plugin
 * @property array url    - home, pro and other URL for plugin
 * @property array rating - website and link for rating
 *
 */
class Wow_Plugin_Admin {

	/**
	 * Setup to admin panel of the plugin
	 *
	 * @param array $info general information about the plugin
	 *
	 * @since 1.0
	 */
	public function __construct( $info ) {
		$this->plugin = $info['plugin'];
		$this->url    = $info['url'];
		$this->rating = $info['rating'];

		add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
		add_filter( 'admin_footer_text', array( $this, 'rate_us' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_style_script' ) );
		add_action( 'wp_ajax_sticky_buttons_message', array( $this, 'wow_message_callback' ) );
		add_action( 'wp_ajax_' . $this->plugin['prefix'] . '_item_save', array( $this, 'item_save' ) );

	}

	public function wow_message_callback() {
		update_option( 'wow_' . $this->plugin['prefix'] . '_message', 'read' );
		wp_die();
	}

	/**
	 * @param string|array $arg text which need show in the tooltip
	 *
	 * @return string tooltip for the element
	 */
	static function tooltip( $arg ) {
		$tooltip = '';
		foreach ( $arg as $key => $value ) {
			if ( $key == 'title' ) {
				$tooltip .= $value . '<p/>';
			} elseif ( $key == 'ul' ) {
				$tooltip .= '<ul>';
				$arr     = $value;
				foreach ( $arr as $val ) {
					$tooltip .= '<li>' . $val . '</li>';
				}
				$tooltip .= '</ul>';
			} else {
				$tooltip .= $value;
			}
		}
		$tooltip = "<span class='wow-help dashicons dashicons-editor-help' title='" . $tooltip . "'></span>";

		return $tooltip;
	}

	/**
	 * @param array $arg parameters for creating field in the backend
	 *
	 * @return string field for displaying
	 */
	static function option( $arg ) {
		$id        = isset( $arg['id'] ) ? esc_attr( $arg['id'] ) : null;
		$name      = isset( $arg['name'] ) ? esc_attr( $arg['name'] ) : '';
		$type      = isset( $arg['type'] ) ? esc_attr( $arg['type'] ) : '';
		$func      = ! empty( $arg['func'] ) ? ' onchange="' . esc_attr( $arg['func'] ) . '"' : '';
		$options   = isset( $arg['option'] ) ? $arg['option'] : '';
		$val       = esc_attr( $arg['val'] );
		$separator = isset( $arg['sep'] ) ? '<' . esc_attr( $arg['sep'] ) . '/>' : '';
		$class     = isset( $arg['class'] ) ? ' class="' . esc_attr( $arg['class'] ) . '"' : '';
		$field     = '';

		if ( ! empty( $options ) ) {
			foreach ( $options as $key => $value ) {
				$key             = esc_attr( $key );
				$options[ $key ] = esc_attr( $value );
			}
		}

		if ( $type == 'radio' ) {
			// create radio fields
			$option = '';
			foreach ( $options as $key => $value ) {
				$select = ( $key == $val ) ? 'checked="checked"' : '';
				$option .= '<input name="' . esc_attr( $name ) . '" type="radio" value="' . esc_attr( $key ) . '" id="'
				           . esc_attr( $id ) . '_' . esc_attr( $key ) . '" ' . $select . $func . $class
				           . '><label for="' . esc_attr( $id ) . '_' . esc_attr( $key ) . '"> ' . esc_attr( $value )
				           . '</label>' . $separator;
			}
			$field = $option;
		} elseif ( $type == 'checkbox' ) {
			// create checkbox field
			$select = ! empty( $val ) ? 'checked="checked"' : '';
			$field  = '<input type="checkbox" ' . $select . $func . $class . ' id="' . esc_attr( $id ) . '" >'
			          . $separator;
			$field  .= '<input type="hidden" name="' . esc_attr( $name ) . '" value="">';
		} elseif ( $type == 'text' || $type == 'number' || $type == 'hidden' ) {
			// create text field
			$option = '';
			if ( is_array( $options ) ) {
				foreach ( $options as $key => $value ) {
					$option .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
				}
			}
			$field
				= '<input name="' . esc_attr( $name ) . '" type="' . esc_attr( $type ) . '" value="' . esc_attr( $val )
				  . '" id="' . esc_attr( $id ) . '"' . $func . $option . $class . '>' . $separator;
		} elseif ( $type == 'color' ) {
			// create color field
			$field = '<input name="' . esc_attr( $name ) . '" type="text" value="' . esc_attr( $val )
			         . '" class="wp-color-picker-field" data-alpha="true" id="' . esc_attr( $id ) . '">' . $separator;
		} // create select field
		elseif ( $type == 'select' ) {
			$disabled = isset( $arg['disabled'] ) ? ' disabled' : '';
			$readonly = isset( $arg['readonly'] ) ? ' readonly' : '';
			$option   = '';
			foreach ( $options as $key => $value ) {
				if ( strrpos( $key, '_start' ) != false ) {
					$option .= '<optgroup label="' . esc_attr( $value ) . '">';
				} elseif ( strrpos( $key, '_end' ) != false ) {
					$option .= '</optgroup>';
				} else {
					$select = ( $key == $val ) ? 'selected="selected"' : '';
					$option .= '<option value="' . esc_attr( $key ) . '" ' . $select . '>' . esc_attr( $value )
					           . '</option>';
				}
			}
			$field = '<select name="' . esc_attr( $name ) . '"' . $func . $class . $disabled . $readonly . ' id="'
			         . esc_attr( $id ) . '">';
			$field .= $option;
			$field .= '</select>' . $separator;
		} elseif ( $type == 'editor' ) {
			// create editor field
			$settings = array(
				'wpautop'       => 0,
				'media_buttons' => 0,
				'textarea_name' => '' . esc_attr( $name ) . '',
				'textarea_rows' => 15,
			);
			$field    = wp_editor( $val, $id, $settings );
		} elseif ( $type == 'textarea' ) {
			// create textarea field
			$option = '';
			if ( is_array( $options ) ) {
				foreach ( $options as $key => $value ) {
					$option .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
				}
			}

			$field = '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '"' . $option . '>'
			         . esc_attr( $val ) . '</textarea>' . $separator;
		}

		return $field;
	}

	/**
	 * @param string $tooltip tooltip for element
	 *
	 * @return string
	 */
	public function pro( $tooltip = null ) {
		$link    = admin_url() . 'admin.php?page=' . $this->plugin['slug'] . '&tab=extension';
		$title   = esc_attr__( 'More features in the PRO version', $this->plugin['text'] );
		$classes = 'wow-help dashicons dashicons-lock';
		$tooltip = ! empty( $tooltip ) ? $title . '<br/>' . $tooltip : $title;
		$pro     = '<a href="' . $link . '" class="' . $classes . '" title="' . $tooltip . '"></a>';

		return $pro;
	}

	/**
	 * Add the plugin page in admin menu
	 *
	 * @since 1.0
	 */
	public function add_admin_page() {
		$parent     = 'wow-company';
		$title      = $this->plugin['name'] . ' version ' . $this->plugin['version'];
		$menu_title = $this->plugin['menu'];
		$capability = 'manage_options';
		$slug       = $this->plugin['slug'];
		$function   = array( $this, 'plugin_page' );
		add_submenu_page( $parent, $title, $menu_title, $capability, $slug, $function );
	}

	/**
	 * Include main plugin page with Style and Script
	 *
	 * @since 1.0
	 */
	public function plugin_page() {
		global $wow_plugin_page;
		$wow_plugin_page = $this->plugin['slug'];
		require_once 'partials/main.php';
	}

	/**
	 * Include Styles and Scripts on the plugin admin page
	 *
	 * @since 1.0
	 */
	public function admin_style_script($hook) {

		$page = 'wow-plugins_page_' . $this->plugin['slug'];

		if ( $page != $hook ) {
			return false;
		}

		$slug       = $this->plugin['slug'];
		$version    = $this->plugin['version'];
		$url_assets = $this->plugin['url'] . 'assets/';

		$pre_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// include the main style
		wp_enqueue_style( $slug . '-admin', $url_assets . 'css/admin-style' . $pre_suffix . '.css', false, $version );

		// include fontAwesome icon
		$url_fontawesome = $url_assets . 'vendors/fontawesome/css/fontawesome-all' . $pre_suffix . '.css';
		wp_enqueue_style( $slug . '-fontawesome', $url_fontawesome, null, '5.14' );

		// include fonticonpicker styles & scripts
		$fonticonpicker_js = $url_assets . 'vendors/fonticonpicker/fonticonpicker.min.js';
		wp_enqueue_script( $slug . '-fonticonpicker', $fonticonpicker_js, array( 'jquery' ) );

		$fonticonpicker_css = $url_assets . 'vendors/fonticonpicker/css/fonticonpicker.min.css';
		wp_enqueue_style( $slug . '-fonticonpicker', $fonticonpicker_css );

		$fonticonpicker_dark_css = $url_assets . 'vendors/fonticonpicker/fonticonpicker.darkgrey.min.css';
		wp_enqueue_style( $slug . '-fonticonpicker-darkgrey', $fonticonpicker_dark_css );

		// include the color picker
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		// include tooltip script
		wp_enqueue_script( 'jquery-ui-tooltip' );

		// include sortable
		wp_enqueue_script( 'jquery-ui-sortable' );


		// include the plugin admin script
		$url_script = $url_assets . 'js/admin-script' . $pre_suffix . '.js';
		wp_enqueue_script( $slug . '-admin', $url_script, array( 'jquery' ), $version, true );


	}

	/**
	 * Add the link to the plugin page on Plugins page
	 *
	 * @param $actions
	 * @param $plugin_file - the plugin main file
	 *
	 * @return mixed
	 */
	public function action_links( $actions, $plugin_file ) {
		if ( false === strpos( $plugin_file, plugin_basename( $this->plugin['file'] ) ) ) {
			return $actions;
		}
		$settings_link
			= '<a href="admin.php?page=' . $this->plugin['slug'] . '">' . esc_attr__( 'Settings',
				$this->plugin['text'] ) . '</a>';
		array_unshift( $actions, $settings_link );

		return $actions;
	}

	/**
	 * Add custom text in the footer on the wow plugin page
	 *
	 * @param $footer_text - text in the footer
	 *
	 * @return string - end text in the footer
	 * @since 1.0
	 */
	public function rate_us( $footer_text ) {
		global $wow_plugin_page;
		if ( $wow_plugin_page == $this->plugin['slug'] ) {
			$rate_text = sprintf( __( 'Thank you for using <a href="%1$s" target="_blank">' . $this->plugin['name']
			                          . '</a>! Please <a href="%2$s" target="_blank">rate us on '
			                          . $this->rating['website'] . '</a>', $this->plugin['text'] ), $this->url['home'],
				$this->rating['url'] );

			return str_replace( '</span>', '', $footer_text ) . ' | ' . $rate_text . '</span>';
		} else {
			return $footer_text;
		}
	}

	/*
	 * Check the post parameter from wow plugin
	 *
	 * @since 1.0
	 */
	public function plugin_check() {
		if ( isset( $_POST[ $this->plugin['slug'] . '_nonce' ] ) ) {
			if ( ! empty( $_POST )
			     && wp_verify_nonce( $_POST[ $this->plugin['slug'] . '_nonce' ], $this->plugin['slug'] . '_action' )
			     && current_user_can( 'manage_options' )
			) {
				self:: save_data();
			}
		}
	}

	/**
	 * Save and Update the Item into the plugin Database
	 *
	 * @return array response from DB
	 *
	 * @since 1.0
	 */
	public function save_data() {
		$save_class    = __NAMESPACE__ . '\\Wow_DB_Update';
		$objItem       = new $save_class( $this->plugin['dir'], $this->plugin['slug'] );
		$add           = ( isset( $_REQUEST['add'] ) ) ? absint( $_REQUEST['add'] ) : '';
		$table         = ( isset( $_REQUEST['data'] ) ) ? sanitize_text_field( $_REQUEST['data'] ) : '';
		$tool_id       = absint( $_POST['tool_id'] );
		$info          = array();
		$info['id']    = $tool_id;
		$info['title'] = wp_unslash( sanitize_text_field( $_POST['title'] ) );
		$info['param'] = array();
		foreach ( $_POST['param'] as $key => $val ) {
			$info['param'][ $key ] = wp_unslash( $val );
		}

		$response = '';
		if ( '1' == $add ) {
			$objItem->create_item( $table, $info );
			$response = array(
				'status'  => 'OK',
				'message' => esc_attr__( 'Item Added', $this->plugin['text'] ),
			);
		} elseif ( '2' == $add ) {

			$objItem->update_item( $table, $info );
			$response = array(
				'status'  => 'OK',
				'message' => esc_attr__( 'Item Updated', $this->plugin['text'] ),
			);
		}

		return $response;
	}

	function item_save() {

		$response = 'No';
		if ( isset( $_POST[ $this->plugin['slug'] . '_nonce' ] ) ) {
			if ( ! empty( $_POST )
			     && wp_verify_nonce( $_POST[ $this->plugin['slug'] . '_nonce' ], $this->plugin['slug'] . '_action' )
			     && current_user_can( 'manage_options' )
			) {
				$response = self:: save_data();
			}
		}

		wp_send_json( $response );

		wp_die();

	}



}

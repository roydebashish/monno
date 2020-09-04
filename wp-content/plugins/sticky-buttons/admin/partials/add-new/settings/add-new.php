<?php
/**
 * Main Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */



$show_option = array(
	'all'        => esc_attr__( 'All posts and pages', $this->plugin['text'] ),
);

$show = array(
	'id'     => 'show',
	'name'   => 'param[show]',
	'type'   => 'select',
	'val'    => isset( $param['show'] ) ? $param['show'] : 'all',
	'option' => $show_option,
	'func'   => 'showchange(this);',
	'sep'    => 'p',
);

$show_help = array(
	'text' => esc_attr__( 'Choose a condition to target to specific content.', $this->plugin['text'] ),
);


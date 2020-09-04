<?php

/* Modula PRO Filter Functions */

// Function to output all filters
function modula_pro_output_filters( $settings ) {

	if ( ! isset( $settings['filters'] ) ) {
		return '';
	}

	$filters = Modula_Pro_Helper::remove_empty_items( $settings['filters'] );

	if ( ! is_array( $filters ) ) {
		return;
	}

	if ( empty( $filters ) ) {
		return;
	}

	$filter_position = (isset($settings['filterPositioning'])) ? $settings['filterPositioning'] : '';

	$before_items = array('top','top_bottom','left','left_right');
	$after_items = array('bottom','top_bottom','right','left_right');
	$horizontal = array('top','bottom','top_bottom');

	$current_filter = isset($_GET['jtg-filter']) ? $_GET['jtg-filter'] : 'all';
	$filter_url = $settings['filterClick']  ? '?jtg-filter=all' : '#';
	$hide_all_filter = $settings['hideAllFilter'];
	$filter_position = $settings['filterPositioning'];

	$extra_classes = (isset($settings['filterStyle'])) ? 'styled-menu menu--'.$settings['filterStyle'] : '';
	$extra_classes .= (isset($filter_position) && in_array($filter_position,$horizontal)) ? ' horizontal-filters' : ' vertical-filters' ;

	if(isset($filter_position) && 'left' == $filter_position) {
		$extra_classes .= ' left-vertical';
	}

	if(isset($filter_position) && 'right' == $filter_position) {
		$extra_classes .= ' right-vertical';
	}

	if(isset($filter_position) && 'left_right' == $filter_position) {
		$extra_classes .= ' both-vertical';
	}

	$filter_by_text = '';
	if(isset($settings['enableCollapsibleFilters']) && '1' == $settings['enableCollapsibleFilters'] && isset($settings['collapsibleActionText'])) {
		$filter_by_text = $settings['collapsibleActionText'];
	}

	$filter_by_wrapper_style = '';
	if( isset($settings['enableCollapsibleFilters']) && '1' == $settings['enableCollapsibleFilters'] && isset( $filter_position ) ){
		$filter_by_wrapper_style = 'display:none;';
	}

	if( doing_filter( 'modula_shortcode_before_items' ) && ! in_array( $filter_position,$before_items ) ){
		return false;
	}

	if( doing_filter( 'modula_shortcode_after_items' ) && ! in_array( $filter_position, $after_items ) ){
		return false;
	}

	if(isset($settings['enableCollapsibleFilters']) && '1' == $settings['enableCollapsibleFilters'] && isset($filter_position)){
		echo '<div class="filter-by-wrapper"><span>'.esc_html( $filter_by_text ).'</span></div>';
	}

	echo "<div class='filters ".esc_attr($extra_classes)."' style='".esc_attr($filter_by_wrapper_style)."'><ul class='menu__list'>";

	if( ! isset( $hide_all_filter ) || '1' != $hide_all_filter ) {
		echo '<li class="menu__item ' . ( 'all' == $current_filter ? 'menu__item--current' : '' ) . '"><a data-filter="all" href="' . esc_url($filter_url) . '" class="' . ( 'all' == $current_filter ? 'selected' : '' ) . ' menu__link ">' . esc_html($settings['allFilterLabel']) . '</a>';
	}

	foreach( $filters as $filter ) {
		$filter_slug = sanitize_title( $filter );
		$filter_url = $settings['filterClick'] ? '?jtg-filter=' . $filter_slug : "#jtg-filter-". $filter_slug;
		echo '<li class="menu__item"><a data-filter="' . esc_attr(urldecode($filter_slug)) . '" href="' . esc_url($filter_url) . '" class=" menu__link  ' . ( $current_filter == $filter_slug ? 'selected' : '' ) . '">' . esc_html($filter) . '</a></li>';
	}
	echo "</div>";

}

// Add filters to items
function modula_pro_add_filters( $item_data, $item, $settings ) {

	if ( isset( $item['filters'] ) ) {
		$filters = explode( ',', $item['filters'] );
		foreach ( $filters as $filter ) {
			$item_data['item_classes'][] = 'jtg-filter-all jtg-filter-' . esc_attr(urldecode(sanitize_title( $filter )));
		}
	}

	return $item_data;

}

// Add extra attributes for facybox
/**
 * @param $item_data
 * @param $item
 * @param $settings
 *
 * @return mixed
 */
function modula_pro_fancybox( $item_data, $item, $settings ) {

	if ( 'fancybox' == $settings['lightbox'] ) {
		$item_data['link_attributes']['data-fancybox'] = esc_attr($settings['gallery_id']);
		$item_data['link_attributes']['data-caption'] = isset( $item_data['link_attributes']['title'] ) ? $item_data['link_attributes']['title'] : $item_data['link_attributes']['data-title'];
	}

	return $item_data;
}

/**
 * @param $extra_classes
 * @param $settings
 *
 * @return string
 *
 * Adds extra class to modula-gallery that is used to position the filters
 */
function modula_pro_extra_modula_section_classes($extra_classes,$settings){
	$filter_position = isset( $settings['filterPositioning'] ) ? $settings['filterPositioning'] : '';
	$horizontal = array( 'top', 'bottom', 'top_bottom' );
	$extra_classes .= in_array( $filter_position, $horizontal ) ? ' horizontal-filters' : ' vertical-filters' ;

	return $extra_classes;
}

/**
 * @return array
 *
 * Return default active filter
 */
function modula_pro_current_active_filter() {
	$id = get_the_ID();
	$settings = get_post_meta($id,'modula-settings',true);
	$filters = isset($settings['filters']) ? $settings['filters'] : false;

	$return = array(
		'All' => esc_html__( 'All','modula-pro' )
	);

	if( $filters ) {
		foreach ( $filters as $filter ) {
			$return[ $filter ] = esc_html( $filter );
		}
	}

	return $return;
}

function modula_pro_sanitize_color( $color ){

	if ( method_exists( 'Modula_Helper', 'sanitize_rgba_colour' ) ) {
		return Modula_Helper::sanitize_rgba_colour( $color );
	}

	if ( empty( $color ) || is_array( $color ) ){
		return 'rgba(0,0,0,0)';
	}
			

	if ( false === strpos( $color, 'rgba' ) ) {
		return sanitize_hex_color( $color );
	}
	
	$color = str_replace( ' ', '', $color );
	sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
	
	return 'rgba(' . absint( $red ) . ',' . absint( $green ) . ',' . absint( $blue ) . ',' . floatval( $alpha ) . ')';

}
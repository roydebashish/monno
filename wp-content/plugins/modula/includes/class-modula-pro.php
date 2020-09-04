<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      2.0.0
 */
class Modula_PRO {

	function __construct() {

		$this->load_dependencies();

		add_action( 'wp_enqueue_scripts', array( $this, 'register_gallery_scripts' ) );

		// Enqueue scripts for selected lightbox
		add_action( 'modula_lighbox_shortcode', array( $this, 'enqueue_selected_lightbox_scripts' ) );

		add_filter( 'modula_necessary_scripts', array( $this, 'enqueue_necessary_scripts' ) );
		add_filter( 'modula_necessary_styles', array( $this, 'modula_necessary_styles' ) );

		// Add Filters for Modula Troubleshooting
		add_filter( 'modula_troubleshooting_fields', array( $this, 'add_troubleshooting_fields' ) );
		add_filter( 'modula_troubleshooting_defaults', array( $this, 'add_troubleshooting_defaults' ) );
		add_filter( 'modula_troubleshooting_frontend_handles', array( $this, 'add_main_pro_files' ), 60, 2 );
		add_filter( 'modula_troubleshooting_frontend_handles', array( $this, 'check_hovereffect' ), 20, 2 );
        add_filter( 'modula_troubleshooting_frontend_handles', array( $this, 'check_linkshortcode' ), 20, 2 );
        add_filter( 'modula_troubleshooting_lightboxes_handles', array( $this, 'add_lightboxes_handles' ), 20, 2 );

		// modula-link shortcode
		add_action( 'init', array( $this, 'add_shortcode' ) );

		// Modify Modula Gallery config
		add_filter( 'modula_gallery_settings', array( $this, 'modula_pro_config' ), 10, 2 );
		add_action( 'modula_shortcode_before_items', 'modula_pro_output_filters', 15 );
		add_action( 'modula_gallery_extra_classes', 'modula_pro_extra_modula_section_classes', 10,2 );
		add_action( 'modula_shortcode_after_items', 'modula_pro_output_filters', 15 );
		add_filter( 'modula_shortcode_item_data', 'modula_pro_add_filters', 30, 3 );
		add_filter( 'modula_shortcode_item_data', 'modula_pro_fancybox', 40, 3 );
		add_filter( 'modula_gallery_images', array( $this, 'modula_pro_max_count' ), 10, 2 );
		add_filter( 'modula_shortcode_after_items', array( $this, 'output_removed_items' ), 10, 2 );
        // Remove Albums upsell metabox
        add_action( 'do_meta_boxes' , array($this, 'remove_albums_upsell_metabox' ), 16, 1);

		// Shortpixel fix
		add_filter( 'modula_shortcode_item_data', array( $this, 'shortpixel_fix' ), 99, 3 );

		// Modify CSS
		add_filter( 'modula_shortcode_css', array( $this, 'generate_new_css' ), 10, 3 );

		// Remove upsells
		add_filter( 'modula_show_upsells', '__return_false' );

		// Output lightboxes options
		add_action( 'modula_extra_scripts', array( $this, 'output_lightboxes_options' ) );
		add_action( 'modula_extra_scripts', array( $this, 'check_for_fonts' ) );

		add_filter('modula_shortcode_item_data',array($this,'modula_pro_extra_lightboxes'),16);

		add_action('modula_extra_scripts',array($this,'output_extra_effects_scripts'));

		add_action('modula_item_after_image',array($this,'extra_effects_extra_elements'));

		add_action('admin_enqueue_scripts',array($this,'preview_extra_effects_scripts'));

		// Add new path for templates
		add_filter( 'modula_template_paths', array( $this, 'add_modula_pro_templates_path' ), 20 );

		// Alter Shortcode column
		$cpt_name = apply_filters( 'modula_cpt_name', 'modula-gallery' );
		add_action( "manage_{$cpt_name}_posts_custom_column" , array( $this, 'output_column' ), 20, 2 );
		add_action( 'modula_admin_after_shortcode_metabox', array( $this, 'output_link_shortcode' ) );

        // Same function because we need to empty the texts
        add_filter('modula_lite_migration_text', array($this, 'migrator_texts'), 15, 1);
        add_filter('modula_importer_upsells', array($this, 'migrator_texts'), 15, 1);

        add_filter('modula_importer_migrate_limit',array($this,'migrator_limit'),15,1);

		if ( is_admin() ) {
			$this->check_for_lite();
		}

	}

	public function add_shortcode(){
		add_shortcode( 'modula-link', array( $this, 'modula_link_shortcode' ) );
	}

	public function modula_pro_extra_lightboxes($item_data){
		$item_data['link_attributes']['data-cyclefilter'] = 'show';
		return $item_data;
	}

	private function load_dependencies() {

		require_once MODULA_PRO_PATH . 'includes/modula-pro-helper-functions.php';
		require_once MODULA_PRO_PATH . 'includes/class-modula-pro-helper.php';
		require_once MODULA_PRO_PATH . 'includes/admin/class-modula-pro-settings.php';

		if ( is_admin() ) {
			require_once MODULA_PRO_PATH . 'includes/admin/class-modula-pro-addon.php';
			require_once MODULA_PRO_PATH . 'includes/admin/modula-pro-addon-ajax.php';
			require_once MODULA_PRO_PATH . 'includes/admin/class-modula-pro-license-activator.php';
		}

	}

	// Register all pro scripts & style in order to be enqueue
	public function register_gallery_scripts() {

		// Register fancybox
		wp_register_style( 'fancybox', MODULA_PRO_URL . 'assets/lightboxes/fancybox/jquery.fancybox.min.css', MODULA_PRO_VERSION, null );
		wp_register_script( 'fancybox', MODULA_PRO_URL . 'assets/lightboxes/fancybox/jquery.fancybox.min.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		// Register lightgallery
		wp_register_style( 'lightgallery', MODULA_PRO_URL . 'assets/lightboxes/lightgallery/css/lightgallery.min.css', MODULA_PRO_VERSION, null );
		wp_register_script( 'lightgallery', MODULA_PRO_URL . 'assets/lightboxes/lightgallery/js/lightgallery.min.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		// Register magnific popup
		wp_register_style( 'magnific-popup', MODULA_PRO_URL . 'assets/lightboxes/magnific/magnific-popup.css', MODULA_PRO_VERSION, null );
		wp_register_script( 'magnific-popup', MODULA_PRO_URL . 'assets/lightboxes/magnific/jquery.magnific-popup.min.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		// Register prettyphoto
		wp_register_style( 'prettyphoto', MODULA_PRO_URL . 'assets/lightboxes/prettyphoto/style.css', MODULA_PRO_VERSION, null );
		wp_register_script( 'prettyphoto', MODULA_PRO_URL . 'assets/lightboxes/prettyphoto/script.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		// Register swipebox
		wp_register_style( 'swipebox', MODULA_PRO_URL . 'assets/lightboxes/swipebox/css/swipebox.min.css', MODULA_PRO_VERSION, null );
		wp_register_script( 'swipebox', MODULA_PRO_URL . 'assets/lightboxes/swipebox/js/jquery.swipebox.min.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		// Modula PRO
		wp_register_style( 'modula-pro-effects', MODULA_PRO_URL . 'assets/css/effects.min.css', MODULA_PRO_VERSION, null );
		wp_register_script( 'modula-pro', MODULA_PRO_URL . 'assets/js/modula-pro.min.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

		// Modula Pro scripts used for Tilt Hover Effects
        wp_register_script( 'modula-pro-tilt', MODULA_PRO_URL . 'assets/js/modula-pro-tilt.min.js', array( 'jquery' ), MODULA_PRO_VERSION, true );

        // Modula Link script
        wp_register_script( 'modula-link-shortcode', MODULA_PRO_URL . 'assets/js/modula-link.js', array( 'jquery' ), MODULA_PRO_VERSION, true );
	}

	// Enqueue specific styles & scripts for each lightbox
	public function enqueue_selected_lightbox_scripts( $lightbox ) {

		switch ( $lightbox ) {
			case 'fancybox':
				wp_enqueue_style( 'fancybox' );
				wp_enqueue_script( 'fancybox' );
				break;
			case 'lightgallery':
				wp_enqueue_style( 'lightgallery' );
				wp_enqueue_script( 'lightgallery' );
				break;
			case 'magnific':
				wp_enqueue_style( 'magnific-popup' );
				wp_enqueue_script( 'magnific-popup' );
				break;
			case 'prettyphoto':
				wp_enqueue_style( 'prettyphoto' );
				wp_enqueue_script( 'prettyphoto' );
				break;
			case 'swipebox':
				wp_enqueue_style( 'swipebox' );
				wp_enqueue_script( 'swipebox' );
				break;
		}

	}

	public function check_for_fonts( $settings ){

		if ( 'Default' == $settings['captionsFontFamily'] && 'Default' == $settings['titleFontFamily'] ) {
			return;
		}

		$fonts = array();

		if ( 'Default' != $settings['titleFontFamily'] ) {
			if ( 'normal' == $settings['titleFontWeight'] ) {
				$fonts[ $settings['titleFontFamily'] ] = array( 300,400,700 );
			}else{
				$fonts[ $settings['titleFontFamily'] ] = array( intval($settings['titleFontWeight']) );
			}
		}

		if ( 'Default' != $settings['captionsFontFamily'] ) {
			if ( 'normal' == $settings['captionFontWeight'] ) {
				$fonts_weights = array( 300,400,700 );
			}else{
				$fonts_weights = array( intval($settings['captionFontWeight']) );
			}

			if ( isset( $fonts[ $settings['captionsFontFamily'] ] ) ) {
				$fonts[ $settings['captionsFontFamily'] ] = array_merge( $fonts[ $settings['captionsFontFamily'] ], $fonts_weights );
			}else{
				$fonts[ $settings['captionsFontFamily'] ] = $fonts_weights;
			}

		}

		if ( empty($fonts) ) {
			return;
		}

		$new_fonts = array();
		foreach ( $fonts as $font => $weights ) {
			$font_name = str_replace(' ', '+', $font );
			$new_fonts[] = $font_name . ':' . implode( ',', array_unique($weights) );
		}

		$fonts_string = implode('|', $new_fonts);
		$font_url = 'https://fonts.googleapis.com/css?family=' . $fonts_string . '&display=swap';
		wp_enqueue_style( 'modula-pro-font', $font_url, MODULA_PRO_VERSION, null );

	}

	public function output_lightboxes_options( $settings ){

		// Add Lightboxes options
		$ligtboxes_options = array(
			'lightboxes' => array(
				'magnific' => array(
	                'options' => array(
	                    'type'     => 'image',
	                    'image'    => array( 'titleSrc' => 'data-title' ),
	                    'gallery'  => array( 'enabled' => 'true' ),
	                    'delegate' => 'a.tile-inner',
                        'fixedContentPos' => 'true'
	                ),
	            ),
	            'prettyphoto' => array(
	                'options' => array( 'social_tools' => '','overlay_gallery_max' => 300 ),
	            ),
	            'fancybox' => array(
	                'options' => array( 'loop' => 'true', 'hash' => false ),
	            ),
	            'swipebox' => array(
	                'options' => array( 'loopAtEnd' => 'true' ),
	            ),
	            'lightgallery' => array(
	                'options' => array( 'selector' => 'a.tile-inner' ),
	            )
			),
		);

		$ligtboxes_options = apply_filters( 'modula_lightboxes_options', $ligtboxes_options, $settings );
		wp_localize_script( 'modula-pro', 'wpModulaProHelper', $ligtboxes_options );

	}

	// Add extra scripts for shortcode to enqueue
	public function enqueue_necessary_scripts( $scripts ) {

		$scripts[] = 'modula-pro';
		return $scripts;

	}

	// Add extra css for shortcode to enqueue
	public function modula_necessary_styles( $styles ) {

		// Search for css for effect in lite and remove it.
		$lite_effects = array_search( 'modula-effects', $styles );
		if ( false !== $lite_effects ) {
			unset( $styles[ $lite_effects ] );
		}

		$styles[] = 'modula-pro';
		$styles[] = 'modula-pro-effects';
		return $styles;

	}

	// Add extra parameter for javascript config
	public function modula_pro_config( $js_config, $settings ) {
		$js_config['lightbox']    = $settings['lightbox'];

		if ( isset( $settings['filterClick'] ) ) {
			$js_config['filterClick'] = esc_attr($settings['filterClick']);
		}

		// if ( isset( $settings['defaultActiveFilter'] ) && 'All' !=  $settings['defaultActiveFilter']) {
		$js_config['defaultActiveFilter'] = esc_attr(sanitize_title($settings['defaultActiveFilter']));
		// }
		return $js_config;
	}

	public function generate_new_css( $css, $gallery_id, $settings ){

        $css .= "#{$gallery_id} .modula-item { background-color:" . modula_pro_sanitize_color($settings['hoverColor']) . "; }";

        if (absint($settings['hoverOpacity']) <= 100) {
            $css .= "#{$gallery_id} .modula-item:hover img { opacity: " . (1 - absint($settings['hoverOpacity']) / 100) . "; }";
		}
		// Settings for cursor preview 
		if ( 'custom' != $settings['cursor'] ) {
		$css .= "#{$gallery_id} .modula-item>a { cursor:" . esc_attr($settings['cursor'])."; } ";
		}else {
			if( $settings['uploadCursor'] != 0 ){
				$image_src = wp_get_attachment_image_src( $settings['uploadCursor'] );
				$css .= "#{$gallery_id} .modula-item>a { cursor:url(" . esc_attr( $image_src[0])."),auto ; } ";
			}
		}

        //Settings for font family caption and title
        if ( 'Default' != $settings['captionsFontFamily'] ) {
        	$css .= "#{$gallery_id} .description{ font-family:" . esc_attr($settings['captionsFontFamily']) . "; }";
        }
        if ( 'Default' != $settings['titleFontFamily'] ) {
        	$css .= "#{$gallery_id} .jtg-title{ font-family:" . esc_attr($settings['titleFontFamily']) . "; }";
        }
        // End of font family caption and title

        //Settings for Title Font Weight
        if ('default' != $settings['titleFontWeight'] ) {
            $css .= "#{$gallery_id} .jtg-title {font-weight:" . esc_attr($settings['titleFontWeight']) . "; }";
        };


        //Settings for Captions Font Weight
        if ('default' != $settings['captionFontWeight'] ) {
            $css .= "#{$gallery_id} p.description {font-weight:" . esc_attr($settings['captionFontWeight']) . "; }";
        }

		$css .= "#{$gallery_id} .modula-item { transform: scale(" . absint($settings['loadedScale']) / 100 . ") translate(" . absint($settings['loadedHSlide']) . 'px,' . absint($settings['loadedVSlide']) . "px) rotate(" . absint($settings['loadedRotate']) . "deg); }";
		
		// Filter Text Alignment 
		if ( 'none' != $settings['filterTextAlignment'] ) {
			$css .= '.modula-gallery .filters .menu__list { text-align: ' . esc_attr( $settings['filterTextAlignment'] ) . ';}';
		}

        if (isset($settings['filterLinkColor']) && '' != $settings['filterLinkColor']) {
            $css .= '.modula-gallery .filters a {color: ' . modula_pro_sanitize_color($settings['filterLinkColor']) . ' !important;}';
        }

        if (isset($settings['filterLinkHoverColor']) && '' != $settings['filterLinkHoverColor']) {

            $css .= '.menu--ceres .menu__item::before, .menu--ceres .menu__item::after, .menu--ceres .menu__link::after,.menu--ariel .menu__item::before, .menu--ariel .menu__item::after, .menu--ariel .menu__link::after, .menu--ariel .menu__link::before{background-color: ' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ' !important;}.modula-gallery .filters a:hover, .modula-gallery .filters li.menu__item--current a {color: ' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ' !important;border-color: ' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ' !important}.modula-gallery .filters li.menu__item--current a:hover:before,.modula-gallery .filters li.menu__item--current a:hover:after,.modula-gallery .filters li.menu__item--current:hover:before,.modula-gallery .filters li.menu__item--current:hover:after { border-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . '}.modula-gallery .filters li.menu__item--current a:before,.modula-gallery .filters li.menu__item--current a:after,.modula-gallery .filters li.menu__item--current:before,.modula-gallery .filters li.menu__item--current:after{border-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . '; background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Antonio
            $css .= '.modula .menu--antonio .menu__item::after, .modula .menu--antonio .menu__item::before, .modula .menu--antonio .menu__link::after, .modula .menu--antonio .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Caliban
            $css .= '.modula .menu--caliban .menu__link::before,.modula .menu--caliban .menu__link::after{border-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Ferdinand
            $css .= '.modula .menu--ferdinand .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Francisco & Trinculo
            $css .= '.modula .menu--francisco .menu__link::before, .modula .menu--trinculo .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Horatio
            $css .= '.modula .menu--horatio .menu__item a::after, .modula .menu--horatio .menu__item a::before, .modula .menu--horatio .menu__item::after, .modula .menu--horatio .menu__item::before{border-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . '}';

            // Invulner
            $css .= '.modula .menu--invulner .menu__link::before{border-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . '}';

            // Iris
            $css .= '.modula .menu--iris .menu__link::after, .modula .menu--iris .menu__link::before{border-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . '}';

            // Juno
            $css .= '.modula .menu--juno .menu__item::after,.modula .menu--juno .menu__item::before,.modula .menu--juno .menu__link::after,.modula .menu--juno .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Maria
            $css .= '.modula .menu--maria .menu__item::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Miranda
            $css .= '.modula .menu--miranda .menu__item::after,.modula .menu--miranda .menu__item::before,.modula .menu--miranda .menu__link::after,.modula .menu--miranda .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Prospero
            $css .= '.modula .menu--prospero .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Sebastian
            $css .= '.modula .menu--sebastian .menu__link::after,.modula .menu--sebastian .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Shylock
            $css .= '.modula .menu--shylock .menu__link::after{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Stephano
            $css .= '.modula .menu--stephano .menu__item::before,.modula .menu--stephano .menu__link::after,.modula .menu--stephano .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // Tantalid
            $css .= '.modula .menu--tantalid .menu__link::after,.modula .menu--tantalid .menu__link::before{border-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . '}';

            // Valentin
            $css .= '.modula .menu--valentine .menu__item::after,.modula .menu--valentine .menu__item::before,.modula .menu--valentine .menu__link::after,.modula .menu--valentine .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

            // 
            $css .= '.modula .menu--viola .menu__item::after,.modula .menu--viola .menu__item::before,.modula .menu--viola .menu__link::after,.modula .menu--viola .menu__link::before{background-color:' . modula_pro_sanitize_color($settings['filterLinkHoverColor']) . ';}';

        }

        // RTL issue fix
        if('lightgallery' == $settings['lightbox']){
            $css .= '.lg-outer.lg-visible .lg-inner {direction:ltr;}';
        }

        if('swipebox' == $settings['lightbox']){
            $css .= '#swipebox-overlay #swipebox-slider {direction:ltr;}';
        }

        return $css;
	}

	// Check if Modula Lite is Active
	private function check_for_lite() {

		$check = array(
			'installed' => Modula_Pro_Helper::check_plugin_is_installed( 'modula-best-grid-gallery' ),
			'active'    => Modula_Pro_Helper::check_plugin_is_active( 'modula-best-grid-gallery' ),
		);

		if ( $check['active'] ) {
			return;
		}

		add_action( 'admin_notices', array( $this, 'display_lite_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_lite_scripts' ) );

	}

	public function display_lite_notice() {

		$check = array(
			'installed' => Modula_Pro_Helper::check_plugin_is_installed( 'modula-best-grid-gallery' ),
			'active'    => Modula_Pro_Helper::check_plugin_is_active( 'modula-best-grid-gallery' ),
		);

		if ( ! $check['installed'] ) {
			$label  = esc_html__( 'Install & Active: Modula Lite', 'modula-pro' );
			$action = 'install';
			$url = '#';
		}else{
			$label  = esc_html__( 'Activate: Modula Lite', 'modula-pro' );
			$action = 'activate';
			$url = add_query_arg(
				array(
					'action'        => 'activate',
					'plugin'        => rawurlencode( Modula_Pro_Helper::_get_plugin_basename_from_slug( 'modula-best-grid-gallery' ) ),
					'plugin_status' => 'all',
					'paged'         => '1',
					'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . Modula_Pro_Helper::_get_plugin_basename_from_slug( 'modula-best-grid-gallery' ) ),
				),
				admin_url( 'plugins.php' )
			);
		}

		echo '<div class="notice" style="background: #e9eff3;padding: 60px;border: 10px solid #fff;text-align:center;">';
		echo '<h1 style="text-align:center">' . esc_html__( 'Install & Activate Modula Lite', 'modula-pro' ) . '</h1>';
		echo '<h4 style="text-align:center;">' . esc_html__( 'Since version 2.0.0 in order for Modula PRO to work properly, you\'ll also need to have Modula Lite installed & activated', 'modula-pro' ) . '</h4>';
		echo '<a href="' . esc_url($url) . '" data-action="' . esc_attr($action) . '" class="button button-primary button-hero" id="install-modula-lite" style="line-height: 23px;padding: 12px 36px;">' . $label . '</a>';
		echo '</div>';

	}

	public function admin_lite_scripts() {
		wp_enqueue_script( 'modula-install-lite', MODULA_PRO_URL . 'assets/js/install-lite.js', array( 'jquery', 'updates' ), null, true );
	}

	public function modula_pro_max_count( $images, $settings ) {

		if ( isset( $settings['maxImagesCount'] ) && absint( $settings['maxImagesCount'] ) > 0 ) {
			$images = array_slice( $images, 0, absint( $settings['maxImagesCount'] ) );
		}

		return $images;
	}

	public function output_removed_items( $settings ){

		if ( absint( $settings['maxImagesCount'] ) == 0 ) {
			return;
		}

		$chuks = explode( '-', $settings['gallery_id'] );
		$gallery_id = $chuks[1];

		$images = get_post_meta( $gallery_id, 'modula-images', true );
		$images = array_slice( $images, absint( $settings['maxImagesCount'] ) );

		echo '<div class="hidden-items">';
		foreach ( $images as $image ) {
			$attr = array(
				'class' => array('tile-inner'),
			);

			if ( isset( $image['filters'] ) ) {
				$filters = explode( ',', $image['filters'] );
				foreach ( $filters as $filter ) {
					$attr['class'][] = 'jtg-filter-all jtg-filter-' . esc_attr(urldecode(sanitize_title( $filter )));
				}
			}

			$image_full = wp_get_attachment_image_src( $image['id'], 'full' );
			if ( is_array( $image_full ) ) {
				$attr['href'] = $image_full[0];
			}

			if ( 'fancybox' == $settings['lightbox'] ) {
				$attr['data-fancybox'] = $settings['gallery_id'];
			}elseif ( 'prettyphoto' == $settings['lightbox'] ) {
				$attr['rel'] = 'prettyPhoto[' . $settings['gallery_id'] . ']';
			}elseif ( 'lightbox2' == $settings['lightbox'] ) {
				$attr['data-lightbox'] = $settings['gallery_id'];
			}else{
				$attr['rel'] = $settings['gallery_id'];
			}

			$caption = isset($image['description']) ? $image['description'] : '';
			if ( '' == $caption ) {
				$caption = wp_get_attachment_caption( $image['id'] );
			}

			if ( in_array( $settings['lightbox'], array( 'prettyphoto', 'swipebox', 'lightbox2' ) ) ) {
				$attr['title'] = $caption;
			}elseif( 'fancybox' == $settings['lightbox'] ){
				$attr['data-caption'] = $caption;
			}else{
				$attr['data-title'] = $caption;
			}

			$attr['data-cyclefilter'] = 'show';

			echo '<a ' . Modula_Helper::generate_attributes( $attr ) . '></a>';
		}
		echo '</div>';
	}

	public function shortpixel_fix( $item_data ){
		if ( isset( $item_data['img_attributes']['src'] ) && strpos( $item_data['img_attributes']['src'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['img_attributes']['src'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['img_attributes']['src'] );
		}
		if ( isset( $item_data['image_full'] ) && strpos( $item_data['image_full'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['image_full'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['image_full'] );
		}

		if ( isset( $item_data['image_url'] ) && strpos( $item_data['image_url'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['image_url'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['image_url'] );
		}
		if ( isset( $item_data['img_attributes']['data-src'] ) && strpos( $item_data['img_attributes']['data-src'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['img_attributes']['data-src'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['img_attributes']['data-src'] );
		}
		if ( isset( $item_data['link_attributes']['href'] ) && strpos( $item_data['link_attributes']['href'], 'modula.shortpixel.ai' ) !== false ) {
			$item_data['link_attributes']['href'] = str_replace( 'modula.shortpixel.ai', 'cdn.wp-modula.com', $item_data['img_attributes']['data-src'] );
		}
		return $item_data;
	}


	/**
	 * Enqueue only the scripts that are required by a specific effect
	 *
	 * @param $settings
	 */
	public function output_extra_effects_scripts($settings){

		$effect = $settings['effect'];
		$tilt_effect = array('tilt_1','tilt_2','tilt_3','tilt_4','tilt_5','tilt_6','tilt_7','tilt_8');

		if(in_array($effect,$tilt_effect)){
            wp_enqueue_script('modula-pro-tilt');
		}
	}


    /**
     * Enqueue needed scripts for the tilt and caption hover effects
     *
     */
	public function preview_extra_effects_scripts(){

        $current_screen = get_current_screen();

        // Register and Enqueue scripts only for Modula Gallery posts
        if ('modula-gallery' == $current_screen->id) {

            // Modula Pro scripts used for Tilt Hover Effects
            wp_register_script('modula-pro-tilt', MODULA_PRO_URL . 'assets/js/modula-pro-tilt.min.js', array('jquery'), MODULA_PRO_VERSION, true);
            wp_enqueue_script('modula-pro-tilt');
        }
    }

    /**
     * Add extra elements for the tilt effect
     *
     * @param $data
     */
	public function extra_effects_extra_elements( $data ){

	    if( count($data->item_classes) > 1 ){
            $hover_effect  = $data->item_classes[1];
            $effect        = explode('-', $hover_effect);
            $effect_array  = array('tilt_1', 'tilt_2', 'tilt_3', 'tilt_4', 'tilt_5', 'tilt_6', 'tilt_7', 'tilt_8');
            $overlay_array = array('tilt_2', 'tilt_3', 'tilt_4', 'tilt_5','tilt_6','tilt_7','tilt_8');
            $svg_array     = array('tilt_1', 'tilt_2', 'tilt_4', 'tilt_5','tilt_6','tilt_7','tilt_8');
            if (in_array($effect[1], $effect_array)) {
                echo '<div class="tilter__deco tilter__deco--shine"><div></div></div>';

                if (in_array($effect[1], $overlay_array)) {
                    echo '<div class="tilter__deco tilter__deco--overlay"></div>';
                }

                if (in_array($effect[1], $svg_array)) {
                    echo '<div class="tilter__deco tilter__deco--lines"></div>';
                }

            }
        }
	}
	
	public function modula_link_shortcode( $atts , $content = null ) {
		
		wp_enqueue_style('fancybox' );
		wp_enqueue_script('fancybox' );
		wp_enqueue_script('modula-link-shortcode');

		if ('' == $atts['id'] ){
			return esc_html( 'Gallery ID not found !' );
		}

		$gallery = get_post( absint( $atts['id'] ) );
		if ( 'modula-gallery' != get_post_type( $gallery ) ){
			return esc_html( 'Gallery ID not found !' );
		}

		$images = get_post_meta( $atts['id'], 'modula-images', true );
		$imagesArray = array();

		if ( ! empty( $images ) ) {
			foreach ( $images as $image ) {
			
				$image_url = wp_get_attachment_image_src( $image['id'], 'full' );
				$image_thumb = wp_get_attachment_image_src( $image['id'] );
				
				$imagesArray[] = array( 'src' => $image_url[0] , 'opts' => array(
					'caption' => $image['title'],
					'thumb'   => $image_thumb[0] 
				) );

			}
		}
		
		return '<a data-images="' . esc_attr( json_encode( $imagesArray ) ) . '"' . 'class="modula-link" href="#">' . do_shortcode($content) . '</a>' ;
		
	}

	public function output_column( $column, $post_id ){

		if ( 'shortcode' == $column ) {
			$shortcode = '[modula-link id="' . $post_id . '"]Click here[/modula-link]';
			echo '<div class="modula-copy-shortcode">';
            echo '<input type="text" value="' . esc_attr($shortcode) . '"  onclick="select()" readonly>';
            echo '<a href="#" title="' . esc_attr__('Copy shortcode','modula-best-grid-gallery') . '" class="copy-modula-shortcode button button-primary dashicons dashicons-format-gallery" style="width:40px;"></a><span></span>';
            echo '</div>';
		}

	}

	public function output_link_shortcode( $post ){
		$shortcode = '[modula-link id="' . $post->ID . '"]Click here[/modula-link]';
		echo '<div class="modula-copy-shortcode">';
        echo '<input type="text" value="' . esc_attr($shortcode) . '"  onclick="select()" readonly>';
        echo '<a href="#" title="' . esc_attr__('Copy shortcode','modula-best-grid-gallery') . '" class="copy-modula-shortcode button button-primary dashicons dashicons-format-gallery" style="width:40px;"></a><span></span>';
        echo '</div>';
	}


    /**
     * Remove Albums upsells metabox
     *
     * @since 2.2.1
     * @param $metaboxes
     * @return array
     */
	public function remove_albums_upsell_metabox(){

	    remove_meta_box( 'modula-albums-upsell', 'modula-gallery', 'normal' );
    }


    /**
     * Migrator texts update
     *
     * @since 2.2.1
     * @param $texts
     * @return string
     */
    public function migrator_texts($texts){

	    $texts = '';
	    return $texts;
    }


    /**
     * Set the image migration limit to 999999999999999
     *
     * @since 2.2.1
     * @param $limit
     * @return int
     */
    public function migrator_limit($limit){

        $limit = '999999999999999';
        return $limit;
    }

    /**
     * Add extra path for modula templates
     *
     * @param $paths
     */
    public function add_modula_pro_templates_path( $paths ){
    	$paths[50] = trailingslashit( MODULA_PRO_PATH ) . '/includes/templates';
    	return $paths;
    }

    /* Modula Troubleshooting */
    public function add_troubleshooting_fields( $fields ){

    	$fields['hover_effects'] = array(
	        'label'       => __('Hover Effects', 'modula-best-grid-gallery'),
	        'description' => __('Check this if you\'re using hover effects with tilt effect', 'modula-best-grid-gallery'),
	        'type'        => 'toggle',
	        'priority'    => 50,
	    );

	    $fields['link_shortcode'] = array(
	        'label'       => __('Link Shortcode', 'modula-best-grid-gallery'),
	        'description' => __('Check this if you\'re using modula link ( [modula-link] ) shortcode', 'modula-best-grid-gallery'),
	        'type'        => 'toggle',
	        'priority'    => 50,
	    );

	    // Add Lightboxes
	    $fields['lightboxes']['values']['magnific']     = esc_html__( 'Magnific popup', 'modula-pro' );
		$fields['lightboxes']['values']['prettyphoto']  = esc_html__( 'PrettyPhoto', 'modula-pro' );
		$fields['lightboxes']['values']['fancybox']     = esc_html__( 'FancyBox', 'modula-pro' );
		$fields['lightboxes']['values']['swipebox']     = esc_html__( 'SwipeBox', 'modula-pro' );
		$fields['lightboxes']['values']['lightbox2']    = esc_html__( 'Lightbox', 'modula-pro' );
		$fields['lightboxes']['values']['lightgallery'] = esc_html__( 'LightGallery', 'modula-pro' );

		return $fields;

    }

    public function add_troubleshooting_defaults( $defaults ){
    	$defaults['hover_effects'] = false;
    	$defaults['link_shortcode'] = false;
    	return $defaults;
    }

    public function add_main_pro_files( $handles ){

    	// remove modula lite effects
		$lite_effects = array_search( 'modula-effects', $handles['styles'] );
		if ( false !== $lite_effects ) {
			unset( $handles['styles'][ $lite_effects ] );
		}

    	// add modula pro main files
        $handles['scripts'][] = 'modula-pro';
        $handles['styles'][]  = 'modula-pro-effects';

        return $handles;
    }

    public function check_hovereffect( $handles, $options ){
        
        if ( $options['hover_effects'] ) {
            $handles['scripts'][] = 'modula-pro-tilt';
        }

        return $handles;

    }

    public function check_linkshortcode( $handles, $options ){
        
        if ( $options['link_shortcode'] ) {
            $handles['scripts'][] = 'modula-link-shortcode';
        }

        return $handles;

    }

    public function add_lightboxes_handles( $lightboxes ){

    	$lightboxes['fancybox'] = array(
            'scripts' => 'fancybox',
            'styles'  => 'fancybox',
        );

        $lightboxes['lightgallery'] = array(
            'scripts' => 'lightgallery',
            'styles'  => 'lightgallery',
        );

        $lightboxes['magnific'] = array(
            'scripts' => 'magnific-popup',
            'styles'  => 'magnific-popup',
        );

        $lightboxes['prettyphoto'] = array(
            'scripts' => 'prettyphoto',
            'styles'  => 'prettyphoto',
        );

        $lightboxes['swipebox'] = array(
            'scripts' => 'swipebox',
            'styles'  => 'swipebox',
        );

    	return $lightboxes;
	}
	

}
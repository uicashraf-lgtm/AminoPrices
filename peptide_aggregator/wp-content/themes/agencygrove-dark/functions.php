<?php

/**
 * AgencyGrove Dark functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @subpackage AgencyGrove Dark
 * @since AgencyGrove Dark 1.0
 */

function agencygrove_dark_block_assets(){
    // Enqueue theme stylesheet for the front-end.
    wp_enqueue_style( 'fontawesome', get_stylesheet_directory_uri() . '/assets/font-awesome/css/all.css', array(), '5.15.3' );
    wp_enqueue_style( 'agencygrove-dark-style', get_stylesheet_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_script('jquery-sticky', get_stylesheet_directory_uri() . '/assets/js/jquery-sticky.js', array('jquery') ); 
    wp_enqueue_script('agencygrove-dark-main-script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery'), '1.0.0', true);

}

add_action('enqueue_block_assets', 'agencygrove_dark_block_assets');

// register own theme pattern

 function agencygrove_dark_register_pattern_category() {
    $patterns = array();
    $block_pattern_categories = array(
        'agencygrove-dark' => array(
            'label' => __( 'AgencyGrove Dark', 'agencygrove-dark' )
        )
    );
    $block_pattern_categories = apply_filters( 'agencygrove_dark_block_pattern_categories', $block_pattern_categories );
    foreach ( $block_pattern_categories as $name => $properties ) {
        if ( ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $name ) ) {
            register_block_pattern_category( $name, $properties );
        }
    }
}
add_action( 'init', 'agencygrove_dark_register_pattern_category');

add_filter('frontpage_template', '__return_empty_string');

add_filter('nav_menu_css_class', function($classes, $item){
    if( is_front_page() && $item->object_id == get_option('page_on_front') ){
        $classes[] = 'current-menu-item';
    }
    return $classes;
}, 10, 2);

/**
 * Shortcode: [pepti_footer_vendors]
 * Renders vendor links fetched from the Python backend API.
 */
function pepti_footer_vendors_shortcode() {
    $client   = new PA_Api_Client();
    $response = $client->request( 'GET', '/api/vendors' );

    if ( ! $response['ok'] || empty( $response['data'] ) ) {
        return '';
    }

    $html = '';
    foreach ( $response['data'] as $vendor ) {
        $name = esc_html( $vendor['name'] );
        $url  = esc_url( $vendor['base_url'] );
        $html .= '<a href="' . $url . '" class="pepti-footer__vendor-link" target="_blank" rel="noopener noreferrer">' . $name . '</a>';
    }
    return $html;
}
add_shortcode( 'pepti_footer_vendors', 'pepti_footer_vendors_shortcode' );
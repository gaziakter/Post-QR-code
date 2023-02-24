<?php
/**
 * Plugin Name:       Post QR Code
 * Plugin URI:        https://criqal.com/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Gazi Akter
 * Author URI:        https://gaziakter/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://criqal.com/
 * Text Domain:       post-qrcode
 * Domain Path:       /languages
 */

//Loading textdomain
function wordcount_load_texdomain(){
    load_plugin_textdomain( 'post-qrcode', false, dirname(__FILE__)."/languages" );
}
add_action( "plugins_loaded", "wordcount_load_texdomain" );

//Display QR code
function pqrc_display_qr_code($content){
    $current_post_id = get_the_ID();   
    $current_post_title = get_the_title( $current_post_id );
    $current_post_url = urlencode(get_the_permalink( $current_post_id ));
   /* 
    $current_post_type = get_the_post_type($current_post_id);

    $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', arrar() );
    if(in_array($current_post_type, $excluded_post_types )){
        retuen $content;
    }


    $image_attributes = apply_filters( 'pqrc_image_attributes', null );
*/
    $image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=%s', $current_post_url);
    $content .= sprintf("<div class='qrcode'><img src='%s' alt='%s' /></div>", $image_src, $current_post_title);
    return $content;
}
add_filter( 'the_content', 'pqrc_display_qr_code');

//Admin pannel setting 
function pqrc_setting_init(){
    add_settings_field( 'pqrc_height', __('QR Code Height', 'post-qrcode'), 'pqrc_display_height','general' );
    add_settings_field( 'pqrc_width', __('QR Code Width', 'post-qrcode'), 'pqrc_display_width','general' );

    register_setting( "general", "pqrc_height", array('sanitize_callback'=>'esc_attr'));
    register_setting( "general", "pqrc_width", array('sanitize_callback'=>'esc_attr'));
}

function pqrc_display_height(){
    $height = get_option( 'pqrc_height');
    printf("<input type='text' id='%s' name='%s' value='%s' />", 'pqrc_height', 'pqrc_height', $height);
}

function pqrc_display_width(){
    $width = get_option('pqrc_width');
    printf("<input type='text' id='%s' name='%s' value='%s' />", 'pqrc_width', 'pqrc_width', $width);
}

add_action( "admin_init", "pqrc_setting_init");
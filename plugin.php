<?php
/**
 * Plugin Name: Awber for Caldera Forms
 * Plugin URI:  https://calderawp.com
 * Description: Awber for Caldera Forms
 * Version:     0.1.0
 * Author:      Josh Pollock for CalderaWP LLC
 * Author URI:  https://CalderaWP.com
 * License:     GPLv2+
 * Text Domain: cf-aweber
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2016 Josh Pollock for CalderaWP LLC (email : Josh@CalderaWP.com) for CalderaWP LLC
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
/**
 * Define constants
 */
define( 'CF_AWBER_VER', '0.1.0' );
define( 'CF_AWBER_URL',     plugin_dir_url( __FILE__ ) );
define( 'CF_AWBER_PATH',    dirname( __FILE__ ) . '/' );
define( 'CF_AWBER_CORE',    dirname( __FILE__ )  );
if ( ! defined( 'CF_AWBER_APP_ID' ) ) {
	define( 'CF_AWBER_APP_ID', '8ac73b3b' );
}





add_action( 'wp_ajax_cf_awber_auth_save', function(){

	if( current_user_can( Caldera_Forms::get_manage_cap( 'admin' ) ) && isset( $_POST[ 'code' ] ) && isset( $_POST[ 'nonce' ] ) && wp_verify_nonce( $_POST[ 'nonce' ] )  ){
		$code = trim( $_POST[ 'code' ] );
		$response = cf_awber_convert_code( $code );
		if( $response ){
			wp_send_json_success();
		}else{
			wp_send_json_error();
		}
	}




});


add_action( 'wp_ajax_cf_aweber_get_lists', function(){
	if( current_user_can( Caldera_Forms::get_manage_cap( 'admin' ) ) && isset( $_GET[ 'nonce' ] ) && wp_verify_nonce( $_GET[ 'nonce' ] ) ){
		CF_Awber_Credentials::get_instance()->set_from_save();
		if( CF_Awber_Credentials::get_instance()->all_set() ){
			$client = new CF_Awber_Client( CF_Awber_Credentials::get_instance() );
			$lists = $client->listLists();
			if( is_array( $lists ) ) {
				wp_send_json_success( array( 'input' => Caldera_Forms_Processor_UI::config_field( cf_awber_lists_field_config() ) ) );
			}
		}

		wp_send_json_error();

	}
	status_header( 404 );
	die();
});


add_filter( 'caldera_forms_processor_ui_input_html', function( $field, $type, $id ){
	if( 'cf-awber-list' == $id ){
		$field .= sprintf( ' <button class="button" id="cf-awber-refresh-lists">%s</button>', esc_html__( 'Refresh Lists', 'cf-awber' ) );
		$field .= '<span id="cf-awber-get-list-spinner" class="spinner" aria-hidden="true"></span>';
	}

	return $field;
}, 10, 3 );

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function cf_awber_init_text_domain() {
	load_plugin_textdomain( 'cf-aweber', FALSE, CF_AWBER_PATH . 'languages' );
}

/**
 * Include Files
 */
// load dependencies
include_once CF_AWBER_PATH . 'vendor/autoload.php';

// pull in the functions file
include CF_AWBER_PATH . 'includes/functions.php';

/**
 * Hooks
 */
//register text domain
add_action( 'init', 'cf_awber_init_text_domain' );


// filter to initialize the license system
add_action( 'admin_init', 'cf_awber_init_license' );

//add our example form
//add_filter( 'caldera_forms_get_form_templates', 'cf_braintree_example_form' );

//load up the processor
add_action( 'caldera_forms_includes_complete', 'cf_awber_load' );

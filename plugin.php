<?php
/**
 * Plugin Name: Awber for Caldera Forms
 * Plugin URI:  https://calderawp.com
 * Description: Awber newsletter integration for Caldera Forms
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
add_action( 'caldera_forms_pre_load_processors', 'cf_awber_load' );

//Save auth via AJAX
add_action( 'wp_ajax_cf_awber_auth_save', 'cf_awber_auth_save_ajax_cb' );

//get lists via AJAX
add_action( 'wp_ajax_cf_aweber_get_lists', 'cf_awber_get_lists_ajax_cb' );

//add refresh lists button to list input
add_filter( 'caldera_forms_processor_ui_input_html', 'cf_awber_processor_ui_input_html', 10, 3 );



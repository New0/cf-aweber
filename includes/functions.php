<?php
/**
 * Functions for Awber for Caldera Forms
 *
 * @package   cf_awber
 * @author    Josh Pollock for CalderaWP LLC (email : Josh@CalderaWP.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock for CalderaWP LLC
 */

/**
 * Load processor
 *
 * @since 0.1.0
 *
 * @uses "caldera_forms_pre_load_processors" action
 */
function cf_awber_load(){
	if( ! class_exists( 'Caldera_Forms_Processor_Newsletter' ) ){
		return;
	}


	cf_awber_register_autload();
	new CF_Awber_Processor( cf_awber_config(), cf_awber_fields(), 'cf-awber' );

}

/**
 * Awber for Caldera Forms config
 *
 * @since 0.1.0
 *
 * @return array	Processor configuration
 */
function cf_awber_config(){

	return array(
		"name"				=>	__( 'Awber for Caldera Forms', 'cf-aweber'),
		"description"		=>	__( 'Awber for Caldera Forms', 'cf-aweber'),
		"icon"				=>	CF_AWBER_URL . "/icon.png",
		"author"			=>	'Josh Pollock for CalderaWP LLC',
		"author_url"		=>	'https://CalderaWP.com',
		"template"			=>	CF_AWBER_PATH . "includes/config.php",

	);


}

/**
 * Get all lists for current account
 *
 * @since 0.1.0
 *
 * @return array|void
 */
function cf_awber_lists(){
	$credentials = cf_awber_main_credentials();
	$set = $credentials->all_set();
	if( ! $set ){
		$credentials->set_from_save();
	}

	$set = $credentials->all_set();
	if( ! $set ){
		return array();
	}

	
	$client = new CF_Awber_Client( $credentials );
	$lists = $client->listLists();
	if( ! empty( $lists ) ){
		$lists = array_combine( wp_list_pluck( $lists, 'id' ), wp_list_pluck( $lists, 'name' ) );
	}
	return $lists;
}

/**
 * Config for lists field
 *
 * @since 0.1.0
 *
 * @return array
 */
function cf_awber_lists_field_config(){
	return array(
		'id'       => 'cf-awber-list',
		'label'    => __( 'List', 'cf-awber' ),
		'desc'     => __( 'List to add subscriber to.', 'cf-awber' ),
		'type'     => 'dropdown',
		'options' => cf_awber_lists(),
		'required' => true,
	);
}

/**
 * Get UI fileds config
 *
 * @since 0.1.0
 *
 * @return array
 */
function cf_awber_fields(){

	$fields = array(
		cf_awber_lists_field_config(),
		array(
			'id'       => 'cf-awber-email',
			'label'    => __( 'Email Address', 'cf-awber' ),
			'desc'     => __( 'Subscriber email address.', 'cf-awber' ),
			'type'     => 'advanced',
			'allow_types' => array( 'email' ),
			'required' => true,
			'magic' => false
		),
		array(
			'id'            => 'cf-awber-name',
			'label'         => __( 'Name', 'cf-awber' ),
			'type'          => 'text',
			'desc'          => __( 'Subscriber name.', 'cf-awber' ),
			'required'      => true,
			'allowed_types' => 'email',
		),
		array(
			'id'    => 'cf-awber-tags',
			'label' => __( 'Tags', 'cf-awber' ),
			'desc'  => __( 'Comma separated list of tags.', 'cf-awber' ),
			'type'  => 'text',
			'required' => false,
		),
		array(
			'id'    => 'cf-awber-misc_notes',
			'label' => __( 'Miscellaneous notes', 'cf-awber' ),
			'type'  => 'text',
			'required' => false,
		),
		array(
			'id'   => 'cf-awber-add_tracking',
			'label' => __( 'Add Tracking', 'cf-awber' ),
			'type'  => 'text',
			'desc' => sprintf( '<a href="%s" target="_blank" title="%s">%s</a> %s.',
				'https://help.aweber.com/hc/en-us/articles/204028836-What-Is-Ad-Tracking-',
				esc_html__( 'Awber ad tracking documentation', 'cf-awber' ),
				esc_html__( 'Value for ad tracking field in Awber.', 'cf-awber' ),
				esc_html__( 'To pass UTM tags use {get:*} magic tags, such as {get:utm_campaign}', 'cf-awber' )
			),
			'required' => false,
			'desc_escaped' => true
		)

	);

	/**
	 * Filter admin UI field configs
	 *
	 * @since 0.1.0
	 *
	 * @param array $fields The fields
	 */
	return apply_filters( 'cf_awber_fields', $fields );
}



/**
 * Initializes the licensing system
 *
 * @uses "admin_init" action
 *
 * @since 0.1.0
 */
function CF_AWBER_init_license(){

	$plugin = array(
		'name'		=>	'Awber for Caldera Forms',
		'slug'		=>	'awber-for-caldera-forms',
		'url'		=>	'https://calderawp.com/',
		'version'	=>	CF_AWBER_VER,
		'key_store'	=>  'CF_AWBER_license',
		'file'		=>  CF_AWBER_CORE,
	);

	new \calderawp\licensing_helper\licensing( $plugin );

}


/**
 * Add our example form
 *
 * @uses "caldera_forms_get_form_templates"
 *
 * @since 0.1.0
 *
 * @param array $forms Example forms.
 *
 * @return array
 */
function CF_AWBER_example_form( $forms ) {
	$forms['cf_awber']	= array(
		'name'	=>	__( 'Contact form with Awber signup.', 'cf-awber' ),
		'template'	=>	include CF_AWBER_PATH . 'includes/templates/example.php'
	);

	return $forms;

}



/**
 * Get the URL for login and get auth code
 *
 * @since 0.1.0
 *
 * @return string
 */
function cf_awber_get_auth_url(){
	$appID = CF_AWBER_APP_ID;
	return "https://auth.aweber.com/1.0/oauth/authorize_app/{$appID}";
}

/**
 * Save auth via AJAX
 *
 * @uses "wp_ajax_cf_awber_auth_save" action
 *
 * @since 0.1.0
 */
function cf_awber_auth_save_ajax_cb(){
	if( current_user_can( Caldera_Forms::get_manage_cap( 'admin' ) ) && isset( $_POST[ 'code' ] ) && isset( $_POST[ 'nonce' ] ) && wp_verify_nonce( $_POST[ 'nonce' ] )  ){
		cf_awber_register_autload();
		$code = trim( $_POST[ 'code' ] );
		$response = cf_awber_convert_code( $code );
		if( ! $response ){
			status_header( 500 );
			wp_send_json_error( array( 'message' => esc_html__( 'Unknown error', 'cf-awber' ) ) );
		}elseif( ! is_wp_error( $response ) ){
			wp_send_json_success();
		}else{
			status_header( 500 );
			wp_send_json_error( $response );
		}
	}

}

/**
 * Convert auth code to keys
 *
 * @since 0.1.0
 *
 * @param string $code Authroization code
 *
 * @return bool
 */
function cf_awber_convert_code( $code ){

	include_once dirname( __FILE__  ) . '/aweber_api/aweber.php';
	cf_awber_register_autload();
	try {
		$credentials = AWeberAPI::getDataFromAweberID($code);
	} catch(AWeberAPIException $exc) {
		return new WP_Error( 'cf-aweber-auth-fail', $exc->message );
	}



	if ( is_array( $credentials ) ) {
		$credentials_object = cf_awber_main_credentials();
		$credentials_object->consumerKey    = $credentials[ 0 ];
		$credentials_object->consumerSecret = $credentials[ 1 ];
		$credentials_object->accessKey      = $credentials[ 2 ];
		$credentials_object->accessSecret   = $credentials[ 3 ];
		return $credentials_object->store();
	}
}


/**
 * Get aweber lists via AJAX
 *
 * @uses "wp_ajax_cf_awber_get_lists" action
 *
 * @since 0.1.0
 */
function cf_awber_get_lists_ajax_cb(){

	if( current_user_can( Caldera_Forms::get_manage_cap( 'admin' ) ) && isset( $_GET[ 'nonce' ] ) && wp_verify_nonce( $_GET[ 'nonce' ] ) ){
		cf_awber_register_autload();
		$credentials = cf_awber_main_credentials();
		$credentials->set_from_save();
		if( cf_awber_main_credentials()->all_set() ){
			$client = new CF_Awber_Client( $credentials );
			$lists = $client->listLists();
			if( is_array( $lists ) ) {
				wp_send_json_success( array( 'input' => Caldera_Forms_Processor_UI::config_field( cf_awber_lists_field_config() ) ) );
			}
		}

		wp_send_json_error();

	}
	status_header( 404 );
	die();

}

/**
 * Get main credentials object
 *
 * @since 0.1.0
 *
 * @return \CF_Awber_Credentials
 */
function cf_awber_main_credentials(){
	global $cf_aweber_main_cred;
	if( ! is_object( $cf_aweber_main_cred ) ){
		$cf_aweber_main_cred = new CF_Awber_Credentials();
	}

	return $cf_aweber_main_cred;

}

/**
 * Add refresh lists button to list input
 *
 * @uses "caldera_forms_processor_ui_input_html" filter
 *
 * @param string $field Field HTML
 * @param string $type Field type
 * @param string $id ID attribute for field
 *
 * @return string
 */
function cf_awber_processor_ui_input_html( $field, $type, $id ){
	if( 'cf-awber-list' == $id ){
		$field .= sprintf( ' <button class="button" id="cf-awber-refresh-lists">%s</button>', esc_html__( 'Refresh Lists', 'cf-awber' ) );
		$field .= '<span id="cf-awber-get-list-spinner" class="spinner" aria-hidden="true"></span>';
	}

	return $field;
}

/**
 * Load up classess
 *
 * @since 0.1.0
 */
function cf_awber_register_autload(){
	include_once CF_AWBER_PATH . 'includes/aweber_api/aweber_api.php';
	Caldera_Forms_Autoloader::add_root( 'CF_Awber', CF_AWBER_PATH . 'classes' );
}

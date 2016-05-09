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
 * @uses "caldera_forms_includes_complete" action
 */
function cf_awber_load(){
	include CF_AWBER_PATH . 'includes/aweber_api/aweber_api.php';
	Caldera_Forms_Autoloader::add_root( 'CF_Awber', CF_AWBER_PATH . 'classes' );
	new CF_Awber_Processor( cf_awber_config(), 'cf-awber' );

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
		"icon"				=>	CF_AWBER_URL . "icon.png",
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
	$client = new CF_Awber_Client( CF_Awber_Credentials::get_instance() );
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
			'type'     => 'text',
			'required' => true,
			'magic' => false
		),
		array(
			'id'            => 'cf-awber-name',
			'label'         => __( 'Name', 'cf-awber' ),
			'type'          => 'text',
			'desc'          => __( 'Subscriber name.', 'cf-awber' ),
			'required'      => true,
			'allowed_types' => 'email'
		),
		array(
			'id'    => 'cf-awber-tags',
			'label' => __( 'Tags', 'cf-awber' ),
			'desc'  => __( 'Comma separated list of tags.', 'cf-awber' ),
			'type'  => 'text',
		),
		array(
			'id'    => 'cf-awber-misc_notes',
			'label' => __( 'Miscellaneous notes', 'cf-awber' ),
			'type'  => 'text',
		),
		array(
			'id'   => 'cf-awber-add_tracking',
			'label' => __( 'Add Tracking', 'cf-awber' ),
			'type'  => 'text',
			'desc' => sprintf( '<a href="%" target="_blank" title="%s">%s</a>.',
				'https://help.aweber.com/hc/en-us/articles/204028836-What-Is-Ad-Tracking-',
				__( 'Awber ad tracking documentation', 'cf-awber' ),
				__( 'Value for ad tracking field in Awber. To pass UTM tags use {get:*} magic tags, such as {get:utm_campaign}', 'cf-awber' )
			)
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
	$forms['cf_aweber']	= array(
		'name'	=>	__( 'Awber for Caldera Forms Example', 'cf-aweber' ),
		'template'	=>	include CF_AWBER_PATH . 'includes/templates/example.php'
	);

	return $forms;

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

	$credentials = AWeberAPI::getDataFromAweberID($code);
	if ( is_array( $credentials ) ) {
		CF_Awber_Credentials::get_instance()->consumerKey    = $credentials[ 0 ];
		CF_Awber_Credentials::get_instance()->consumerSecret = $credentials[ 1 ];
		CF_Awber_Credentials::get_instance()->accessKey      = $credentials[ 2 ];
		CF_Awber_Credentials::get_instance()->accessSecret   = $credentials[ 3 ];
		return CF_Awber_Credentials::get_instance()->store();
	}
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

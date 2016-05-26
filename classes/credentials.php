<?php
/**
 * Class CF_Awber_Credentials
 *
 * @package   cf_awber
 * @author    Josh Pollock for CalderaWP LLC (email : Josh@CalderaWP.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock for CalderaWP LLC
 */
class CF_Awber_Credentials extends CF_Awber_Base {

	/**
	 * Key to store this credentials set in
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $option_key;

	/**
	 * CF_Awber_Credentials constructor.
	 *
	 * @param string $option_key Key for storing this credentials set
	 */
	public function __construct( $option_key = '_cf_awber_main_credentials') {
		$this->option_key = $option_key;
	}

	/**
	 * Save the credentials
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function store(){
		if( 0 == get_option( $this->option_key, 0 ) ){
			return add_option( $this->option_key, $this->deflate() );
		}else{
			return update_option($this->option_key, $this->deflate() );
		}


	}

	/**
	 * Check if all of our properties are set
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function all_set(){
		foreach(  get_object_vars( $this ) as $prop => $value ){
			if( ! isset( $this->$prop ) ){
				return false;
			}
		}

		return true;
	}

	/**
	 * Get saved settings and set properties
	 *
	 * @since 0.1.0
	 */
	public function set_from_save(){
		$saved = get_option( $this->option_key, array() );
		if( ! empty( $saved ) ){
			foreach(  get_object_vars( $this ) as $prop => $value ){
				if( isset( $saved[ $prop ] ) ){
					$this->$prop = $saved[ $prop ];
				}
			}
		}


	}

	/**
	 *  Prepare object properites to be saved
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	protected function deflate(){
		return get_object_vars( $this );
	}

}

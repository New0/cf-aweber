<?php


///THIS IS ALL FUCKED BEACUSE SHOULDN'T BE ONE SINGLETON - should be one instance per form, but that's annoying since you would need to reauth all the dammn time.

//@TODO THINK MORE ABOUT THIS AND REDO IT...


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

	private static $instance;

	protected function __construct(){}
	public static function get_instance(){
		if( null == self::$instance ){
			self::$instance = new self();
		}

		return self::$instance;
	}


	public function store(){
		if( 0 == get_option( __CLASS__, 0 ) ){
			return add_option( __CLASS__, $this->deflate() );
		}else{
			return update_option(__CLASS__, $this->deflate() );
		}


	}

	public function all_set(){
		foreach(  get_object_vars( $this ) as $prop => $value ){
			if( ! isset( $this->$prop ) ){
				return false;
			}
		}

		return true;
	}

	public function set_from_save(){
		$this->inflate();
	}

	protected function inflate(){
		$saved = get_option( __CLASS__, array() );
		if( ! empty( $saved ) ){
			foreach(  get_object_vars( $this ) as $prop => $value ){
				if( isset( $saved[ $prop ] ) ){
					$this->$prop = $saved[ $prop ];
				}
			}
		}


	}

	protected function deflate(){
		return get_object_vars( $this );
	}






}

<?php
/**
 * Class CF_Awber_Client
 *
 * @package   cf_awber
 * @author    Josh Pollock for CalderaWP LLC (email : Josh@CalderaWP.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock for CalderaWP LLC
 */
class CF_Awber_Client extends  CF_Awber_Base {
	/**
	 * Awber application object
	 *
	 * @since 0.1.0
	 *
	 * @var \AWeberAPI
	 */
	public $application;

	/**
	 * Awber account object
	 *
	 * @since 0.1.0
	 *
	 * @var  AWeberCollection
	 */
	public $account;

	/**
	 * Construct object
	 *
	 * @since 0.1.0
	 *
	 * @param \CF_Awber_Credentials $credentials
	 */
	function __construct( CF_Awber_Credentials $credentials ) {

		$this->consumerKey = $credentials->consumerKey;
		$this->consumerSecret = $credentials->consumerSecret;
		$this->accessKey = $credentials->accessKey;
		$this->accessSecret = $credentials->accessSecret;

		$this->application = new AWeberAPI($this->consumerKey, $this->consumerSecret);


	}

	/**
	 * Set account
	 *
	 * @since 0.1.0
	 */
	public function set_account( $accesKey = '', $accountSecret = '' ){

		$this->account = $this->application->getAccount($this->accessKey, $this->accessSecret );
	}

	/**
	 * List all lists of this account
	 *
	 * @since 0.1.0
	 *
	 * @return array|void
	 */
	function listLists(){
		if( empty( $this->account ) ){
			$this->set_account();
		}

		if( empty( $this->account ) ){
			return;
		}

		$url = $this->account->data[ 'lists_collection_link' ];
		$response = $this->application->adapter->request('GET', $url);

		if( is_array( $response ) && isset( $response[ 'entries' ] ) ){

			return $response[ 'entries' ];
		}

		return array();

	}


	/**
	 * Get a all list from this account
	 *
	 * @since 0.1.0
	 *
	 * @return array|void
	 */
	function findList($listName ) {
		try {
			$foundLists = $this->account->lists->find(array('name' => $listName));
			//must pass an associative array to the find method

			return $foundLists[0];
		}

		catch(Exception $exc) {
			print $exc;
		}
	}

	/**
	 * Find a subscriber to to any list in this account
	 *
	 * @since 0.1.0
	 *
	 * @return array|void
	 */
	function findSubscriber($email) {
		try {
			$foundSubscribers = $this->account->findSubscribers(array('email' => $email));
			//must pass an associative array to the find method

			return $foundSubscribers[0];
		}

		catch(Exception $exc) {
			print $exc;
		}
	}

	/**
	 * Add a subscriber to to any list in this account
	 *
	 * @since 0.1.0
	 *
	 * @return array|void
	 */
	function addSubscriber($subscriber, $list) {
		try {
			$listUrl = "/accounts/{$this->account->id}/lists/{$list->id}";
			$list = $this->account->loadFromUrl($listUrl);

			$newSubscriber = $list->subscribers->create($subscriber);
			return $newSubscriber;
		}

		catch(Exception $exc) {
			print $exc;
		}
	}
}

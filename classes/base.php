<?php

/**
 * Class CF_Awber_Base
 *
 * @package   cf_awber
 * @author    Josh Pollock for CalderaWP LLC (email : Josh@CalderaWP.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock for CalderaWP LLC
 */
abstract class CF_Awber_Base {

	/**
	 * Access key
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $accessKey;

	/**
	 * Access secret
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $accessSecret;

	/**
	 * Consumer  key
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $consumerKey;

	/**
	 * Consumer secret key
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $consumerSecret;
}

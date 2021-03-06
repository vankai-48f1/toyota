<?php
/**
 * Abstract CDFS session
 *
 * @package CDFS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle data for the current users session
 *
 * @author      PotenzaGlobalSolutions
 */
abstract class CDFS_Session {

	/**
	 * Customer id
	 *
	 *  @var int $_customer_id .
	 */
	protected $_customer_id;

	/**
	 * Data
	 *
	 * @var array $_data .
	 */
	protected $_data = array();

	/**
	 * Dirty
	 *
	 * @var bool $_dirty When something changes.
	 */
	protected $_dirty = false;

	/**
	 * __get function.
	 *
	 * @param mixed $key .
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->get( $key );
	}

	/**
	 * __set function.
	 *
	 * @param mixed $key .
	 * @param mixed $value .
	 */
	public function __set( $key, $value ) {
		$this->set( $key, $value );
	}

	/**
	 * __isset function.
	 *
	 * @param mixed $key .
	 * @return bool
	 */
	public function __isset( $key ) {
		return isset( $this->_data[ sanitize_title( $key ) ] );
	}

	/**
	 * __unset function.
	 *
	 * @param mixed $key .
	 */
	public function __unset( $key ) {
		if ( isset( $this->_data[ $key ] ) ) {
			unset( $this->_data[ $key ] );
			$this->_dirty = true;
		}
	}

	/**
	 * Get a session variable.
	 *
	 * @param string $key .
	 * @param mixed  $default used if the session variable isn't set.
	 * @return array|string value of session variable
	 */
	public function get( $key, $default = null ) {
		$key = sanitize_key( $key );
		return isset( $this->_data[ $key ] ) ? maybe_unserialize( $this->_data[ $key ] ) : $default;
	}

	/**
	 * Set a session variable.
	 *
	 * @param string $key .
	 * @param mixed  $value .
	 */
	public function set( $key, $value ) {
		if ( $value !== $this->get( $key ) ) {
			$this->_data[ sanitize_key( $key ) ] = maybe_serialize( $value );
			$this->_dirty                        = true;
		}
	}

	/**
	 * Get_customer_id function.
	 *
	 * @access public
	 * @return int
	 */
	public function get_customer_id() {
		return $this->_customer_id;
	}
}

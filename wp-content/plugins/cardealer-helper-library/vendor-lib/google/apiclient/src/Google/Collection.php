<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase

if ( ! class_exists( 'Google_Client' ) ) {
	require_once dirname( __FILE__ ) . '/autoload.php';
}

/**
 * Extension to the regular Google_Model that automatically
 * exposes the items array for iteration, so you can just
 * iterate over the object rather than a reference inside.
 */
class Google_Collection extends Google_Model implements Iterator, Countable {
	/**
	 * Member variable
	 *
	 * @var $collection_key .
	 */
	protected $collection_key = 'items';

	/**
	 * Rewind
	 */
	public function rewind() {
		if ( isset( $this->modelData[ $this->collection_key ] )
		&& is_array( $this->modelData[ $this->collection_key ] ) ) {
			reset( $this->modelData[ $this->collection_key ] );
		}
	}

	/**
	 * Current
	 */
	public function current() {
		$this->coerceType( $this->key() );
		if ( is_array( $this->modelData[ $this->collection_key ] ) ) {
			return current( $this->modelData[ $this->collection_key ] );
		}
	}

	/**
	 * Key
	 */
	public function key() {
		if ( isset( $this->modelData[ $this->collection_key ] )
		&& is_array( $this->modelData[ $this->collection_key ] ) ) {
			return key( $this->modelData[ $this->collection_key ] );
		}
	}

	/**
	 * Next
	 */
	public function next() {
		return next( $this->modelData[ $this->collection_key ] );
	}

	/**
	 * Valid
	 */
	public function valid() {
		$key = $this->key();
		return $key !== null && $key !== false;
	}

	/**
	 * Count
	 */
	public function count() {
		if ( ! isset( $this->modelData[ $this->collection_key ] ) ) {
			return 0;
		}
		return count( $this->modelData[ $this->collection_key ] );
	}

	/**
	 * Offset Exists
	 *
	 * @param string $offset .
	 */
	public function offsetExists( $offset ) {
		if ( ! is_numeric( $offset ) ) {
			return parent::offsetExists( $offset );
		}
		return isset( $this->modelData[ $this->collection_key ][ $offset ] );
	}

	/**
	 * Offset Get
	 *
	 * @param string $offset .
	 */
	public function offsetGet( $offset ) {
		if ( ! is_numeric( $offset ) ) {
			return parent::offsetGet( $offset );
		}
		$this->coerceType( $offset );
		return $this->modelData[ $this->collection_key ][ $offset ];
	}

	/**
	 * Offset Set
	 *
	 * @param string $offset .
	 * @param string $value .
	 */
	public function offsetSet( $offset, $value ) {
		if ( ! is_numeric( $offset ) ) {
			return parent::offsetSet( $offset, $value );
		}
		$this->modelData[ $this->collection_key ][ $offset ] = $value;
	}

	/**
	 * Offset Unset
	 *
	 * @param string $offset .
	 */
	public function offsetUnset( $offset ) {
		if ( ! is_numeric( $offset ) ) {
			return parent::offsetUnset( $offset );
		}
		unset( $this->modelData[ $this->collection_key ][ $offset ] );
	}

	/**
	 * CoerceType
	 *
	 * @param string $offset .
	 */
	private function coerceType( $offset ) {
		$typeKey = $this->keyType( $this->collection_key );
		if ( isset( $this->$typeKey ) && ! is_object( $this->modelData[ $this->collection_key ][ $offset ] ) ) {
			$type = $this->$typeKey;
			$this->modelData[ $this->collection_key ][ $offset ] =
			new $type( $this->modelData[ $this->collection_key ][ $offset ] );
		}
	}
}

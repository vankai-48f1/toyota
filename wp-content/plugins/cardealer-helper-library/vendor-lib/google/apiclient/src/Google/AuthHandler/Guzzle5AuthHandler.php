<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase

use Google\Auth\CredentialsLoader;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Google\Auth\Subscriber\AuthTokenSubscriber;
use Google\Auth\Subscriber\ScopedAccessTokenSubscriber;
use Google\Auth\Subscriber\SimpleSubscriber;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * AuthHandler
 */
class Google_AuthHandler_Guzzle5AuthHandler {

	/**
	 * Member variable
	 *
	 * @var $cache
	 */
	protected $cache;
	/**
	 * Member variable
	 *
	 * @var $cacheConfig
	 */
	protected $cacheConfig;

	/**
	 * Construct
	 *
	 * @param array $cache .
	 * @param array $cacheConfig .
	 */
	public function __construct( CacheItemPoolInterface $cache = null, array $cacheConfig = array() ) {
		$this->cache       = $cache;
		$this->cacheConfig = $cacheConfig;
	}

	/**
	 * Attach Credentials
	 *
	 * @param string $http .
	 * @param string $credentials .
	 * @param string $tokenCallback .
	 */
	public function attachCredentials( ClientInterface $http, CredentialsLoader $credentials, callable $tokenCallback = null ) {
		// if we end up needing to make an HTTP request to retrieve credentials, we
		// can use our existing one, but we need to throw exceptions so the error
		// bubbles up.
		$authHttp        = $this->createAuthHttp( $http );
		$authHttpHandler = HttpHandlerFactory::build( $authHttp );
		$subscriber      = new AuthTokenSubscriber(
			$credentials,
			$this->cacheConfig,
			$this->cache,
			$authHttpHandler,
			$tokenCallback
		);

		$http->setDefaultOption( 'auth', 'google_auth' );
		$http->getEmitter()->attach( $subscriber );

		return $http;
	}

	/**
	 * Attach Token
	 *
	 * @param array $http .
	 * @param array $token .
	 * @param array $scopes .
	 */
	public function attachToken( ClientInterface $http, array $token, array $scopes ) {
		$tokenFunc = function ( $scopes ) use ( $token ) {
			return $token['access_token'];
		};

		$subscriber = new ScopedAccessTokenSubscriber(
			$tokenFunc,
			$scopes,
			array(),
			$this->cache
		);

		$http->setDefaultOption( 'auth', 'scoped' );
		$http->getEmitter()->attach( $subscriber );

		return $http;
	}

	/**
	 * Attach Key
	 *
	 * @param string $http .
	 * @param string $key .
	 */
	public function attachKey( ClientInterface $http, $key ) {
		$subscriber = new SimpleSubscriber( array( 'key' => $key ) );

		$http->setDefaultOption( 'auth', 'simple' );
		$http->getEmitter()->attach( $subscriber );

		return $http;
	}

	/**
	 * Create AuthHttp
	 *
	 * @param string $http .
	 */
	private function createAuthHttp( ClientInterface $http ) {
		return new Client(
			array(
				'base_url' => $http->getBaseUrl(),
				'defaults' => array(
					'exceptions' => true,
					'verify'     => $http->getDefaultOption( 'verify' ),
					'proxy'      => $http->getDefaultOption( 'proxy' ),
				),
			)
		);
	}
}

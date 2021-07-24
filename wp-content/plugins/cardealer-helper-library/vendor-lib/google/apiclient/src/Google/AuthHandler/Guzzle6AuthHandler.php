<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase

use Google\Auth\CredentialsLoader;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Google\Auth\Middleware\AuthTokenMiddleware;
use Google\Auth\Middleware\ScopedAccessTokenMiddleware;
use Google\Auth\Middleware\SimpleMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Auth Handler
 */
class Google_AuthHandler_Guzzle6AuthHandler {
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
	 * @param string $cache .
	 * @param array  $cacheConfig .
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
		$middleware      = new AuthTokenMiddleware(
			$credentials,
			$this->cacheConfig,
			$this->cache,
			$authHttpHandler,
			$tokenCallback
		);

		$config = $http->getConfig();
		$config['handler']->remove( 'google_auth' );
		$config['handler']->push( $middleware, 'google_auth' );
		$config['auth'] = 'google_auth';
		$http           = new Client( $config );

		return $http;
	}

	/**
	 * Attach Token
	 *
	 * @param string $http .
	 * @param array  $token .
	 * @param array  $scopes .
	 */
	public function attachToken( ClientInterface $http, array $token, array $scopes ) {
		$tokenFunc = function ( $scopes ) use ( $token ) {
			return $token['access_token'];
		};

		$middleware = new ScopedAccessTokenMiddleware(
			$tokenFunc,
			$scopes,
			array(),
			$this->cache
		);

		$config = $http->getConfig();
		$config['handler']->remove( 'google_auth' );
		$config['handler']->push( $middleware, 'google_auth' );
		$config['auth'] = 'scoped';
		$http           = new Client( $config );

		return $http;
	}

	/**
	 * Attach Key
	 *
	 * @param string $http .
	 * @param string $key .
	 */
	public function attachKey( ClientInterface $http, $key ) {
		$middleware = new SimpleMiddleware( array( 'key' => $key ) );

		$config = $http->getConfig();
		$config['handler']->remove( 'google_auth' );
		$config['handler']->push( $middleware, 'google_auth' );
		$config['auth'] = 'simple';
		$http           = new Client( $config );

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
				'base_uri'   => $http->getConfig( 'base_uri' ),
				'exceptions' => true,
				'verify'     => $http->getConfig( 'verify' ),
				'proxy'      => $http->getConfig( 'proxy' ),
			)
		);
	}
}

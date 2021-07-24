<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class Google_Service {

	/**
	 * Member variable
	 *
	 * @var $batchPath .
	 */
	public $batchPath;
	/**
	 * Member variable
	 *
	 * @var $rootUrl .
	 */
	public $rootUrl;
	/**
	 * Member variable
	 *
	 * @var $version .
	 */
	public $version;
	/**
	 * Member variable
	 *
	 * @var $servicePath .
	 */
	public $servicePath;
	/**
	 * Member variable
	 *
	 * @var $availableScopes .
	 */
	public $availableScopes;
	/**
	 * Member variable
	 *
	 * @var $resource .
	 */
	public $resource;
	/**
	 * Member variable
	 *
	 * @var $client .
	 */
	private $client;

	/**
	 * Construct
	 *
	 * @param Google_Client $client .
	 */
	public function __construct( Google_Client $client ) {
		$this->client = $client;
	}

	/**
	 * Return the associated Google_Client class.
	 *
	 * @return Google_Client
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Create a new HTTP Batch handler for this service
	 *
	 * @return Google_Http_Batch
	 */
	public function createBatch() {
		return new Google_Http_Batch(
			$this->client,
			false,
			$this->rootUrl,
			$this->batchPath
		);
	}
}

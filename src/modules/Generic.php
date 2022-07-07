<?php

namespace Rededor\Scheduling\Modules;

use Laminas\Http\Client;

abstract class Generic {

	const METHOD_GET = 'get';
    const METHOD_POST = 'post';

	protected $endpoint = null;	
	protected $clientId = null;
	protected $accessToken = null;
	

	function __construct($endpoint, $clientId = null, $accessToken = null) {
		$this->endpoint = $endpoint;
		$this->clientId = $clientId;
		$this->accessToken = $accessToken;
	}

	public function setAccessToken($accessToken) {
		$this->accessToken = $accessToken;
	}

	private function request($method, $url, $parameters = [], $options = []) {

		$options = array_merge(['headers' => []], $options);
		$headers = [];

		if (!empty($options['headers'])) {
			$headers = $options['headers'];
		}	

		$client = new Client();
		$client->setUri($this->endpoint . $url);

		var_dump($this->endpoint . $url);
		
		if (!empty($this->accessToken)) {
			$headers['client_id'] = $this->clientId;
			$headers['access_token'] = $this->accessToken;
		}

		switch ($method) {
			case static::METHOD_GET:
				$client->setParameterGet($parameters);	
			break;
			case static::METHOD_POST:
				$client->setMethod('POST');
				$client->setParameterPost($parameters);	
			break;
		}

		if (!empty($headers)) {
			
			$client->setHeaders($headers);
		}



		$response = $client->send();	

		return $this->parseResponse($response);
	}



	protected function _get($url, $parameters = [], $options = []) {

		$result = $this->request(
			static::METHOD_GET, 
			$url, 
			$parameters, 
			$options
		);
		
		return $result;
	}



	protected function _post($url, $parameters = [], $options = []) {
		
		return $this->request(
			static::METHOD_POST, 
			$url, 
			$parameters, 
			$options
		);
	}



	private function parseResponse($response) {

		if (!$response->isSuccess()) {
			$body = json_decode($response->getBody(), true);
			throw new \Exception($body['message']);
			
		}

		$responseJson = json_decode($response->getBody(), true);

		return $responseJson;
	} 

}
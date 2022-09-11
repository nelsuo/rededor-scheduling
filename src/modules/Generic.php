<?php

namespace Rededor\Scheduling\Modules;

use Laminas\Http\Client;


/**
 * This class describes a sdk module, these are the basic methods to wrap a request to the Rede D'or API
 */
abstract class Generic {

	const METHOD_GET = 'get';
    const METHOD_POST = 'post';

    protected $sdk = null;
    protected $name = null;
	protected $endpoint = null;	
	
	/**
	 * Constructs a new instance.
	 *
	 * @param      object  $sdk 		 The class the controls the interaction
	 * @param      string  $endpoint     The endpoint
	 */
	function __construct($sdk, $endpoint) {
		$this->sdk = $sdk;
		$this->endpoint = $endpoint;
	}

	/**
	 * Makes a get request on the module, width the given parameters.
	 *
	 * @param      array   $parameters  The parameters
	 *
	 * @return     array  The response array.
	 */
	public function get($parameters = [], $options = []) {

    	$response = $this->request(
			static::METHOD_GET, 
			'/' . $this->getConfig('route'), 
			$parameters,
			$options
		);

        return $response;
    }

    /**
     * Makes a post request on the module, width the given parameters.
     *
     * @param      array   $parameters  The parameters
     *
     * @return     array  	The response array.
     */
    public function post($parameters = [], $options = []) {

    	pr($parameters);

    	$response = $this->request(
			static::METHOD_POST, 
			'/' . $this->getConfig('route'), 
			$parameters,
			$options
		);
    	
        return $response;
    }


    /**
     * Validates the parameters that are passed to the get and the post methods.
     *
     * @param      array  $parameters  The parameters
     *
     * @return     bool   if request is valid
     */
    public function validateParameters($method, $parameters = []) {

    	$config = $this->getConfig('methods');
    	if (empty($config[$method])) {
    		throw new \Exception('Module method not defined ' . $method);
    	}

    	// will map the parameter array in a more usable structure.
    	$methodParameters = !empty($config[$method]['parameters']) ?
    		array_map(function ($p) {
    			$parts = explode(':', $p);

    			$param = [
    				'code' => $parts[0],
    				'type' => 'string',
    				'required' => false
    			];	

    			if (!empty($parts[1]) && $parts[1] === 'required') {
    				$param['required'] = true;
    			}

    			return $param;
    		}, $config[$method]['parameters']) :
    		[];

    	
    	$processedParameters = [];
    	foreach ($methodParameters as $p) {
    		$pCode = $p['code'];

    		if (strpos($pCode, '.') === false) {
    			$processedParameters[$pCode] = $p;
    			continue;
    		}

    		$parts = explode('.', $pCode);
    		$mpCode = $parts[0];
    		
    		if (empty($processedParameters[$mpCode])) {
    			$processedParameters[$mpCode] = [
    				'code' => $mpCode,
    				'type' => 'array',
    				'required' => false,
    				'fields' => []
    			];
    		} 

    		$processedParameters[$mpCode]['fields'][$parts[1]] = [
    			'code' => $parts[1],
    			'type' => $p['type'],
    			'required' => $p['required']
    		];	

    		if ($p['required'] === true) {
    			$processedParameters[$mpCode]['required'] = true;
    		}
    	}

    	// check if all required parameters are sent.
    	foreach ($processedParameters as $p) {
    		if ($p['required'] && !isset($parameters[$p['code']])) {
    			throw new \Exception('Missing required parameter ' . $p['code']);
    		}
    		if ($p['type'] === 'array') {
    			foreach ($p['fields'] as $sp) {
    				if ($sp['required'] && empty($parameters[$p['code']][$sp['code']])) {
		    			throw new \Exception('Missing required parameter ' . $sp['code'] . ' on ' . $p['code']);
		    		}
    			}

    			foreach (array_keys($parameters[$p['code']]) as $px) {
		    		if (!isset($p['fields'][$px])) {
		    			throw new \Exception('Unexpected parameter: ' . $px . ' on ' . $p['code']);
		    		}
		    	}

    		}
    	}

    	// check if there are extra parameters not required by the method.
    	foreach (array_keys($parameters) as $p) {
    		if (!isset($processedParameters[$p])) {
    			throw new \Exception('Unexpected parameter: ' . $p);
    		}
    	}

    	return true;

    }



    /**
     * Gets a module configuration.
     *
     * @param      <type>      $key    The key
     *
     * @throws     \Exception  (description)
     *
     * @return     mixed      The configuration value.
     */
    public function getConfig($key) {
    	if (empty($this->config[$key])) {
    		throw new \Exception('Module config key not defined');
    	}

    	return $this->config[$key];
    }


    /**
     * Will make the actual request
     *
     * @param      <type>  $method      The method
     * @param      <type>  $url         The url
     * @param      array   $parameters  The parameters
     * @param      array   $options     The options
     *
     * @return     array     The response array.
     */
	private function request($method, $url, $parameters = [], $options = []) {

		$this->validateParameters($method, $parameters);


		$options = array_merge(['headers' => []], $options);
		$headers = [];

		if (!empty($options['headers'])) {
			$headers = $options['headers'];
		}	

		$client = new Client(
			$this->endpoint . $url,
			[
				'timeout' => 60,
			]
		);

		$accessToken = $this->sdk->getAccessToken();

		if (!empty($accessToken)) {
			$headers['client_id'] = $this->sdk->getClientId();
			$headers['access_token'] = $accessToken;
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

		try {
			$output = $this->parseResponse($response);	
		} catch (\Exception $exception) {

			if ($exception->getMessage() === 'INVALID_ACCESS_TOKEN' && empty($options['retrying'])) {
				
				$this->sdk->recoverToken();

				$options['retrying'] = true;

				return $this->request($method, $url, $parameters, $options);

			} else {
				throw $exception;
			}


		}
		

		return $output;
	}




	/**
	 * Will process an API reply response and simplify the response.
	 *
	 * @param      <type>      $response  The response
	 *
	 * @throws     \Exception  The exception generated on the API site
	 *
	 * @return     <type>      The response array.
	 */
	private function parseResponse($response) {

		if (!$response->isSuccess()) {
			$body = json_decode($response->getBody(), true);
			if (!$body) {
				$body = $response->getBody();
				if (strpos($body, 'Could not find a required Access Token in the request') === 0) {
					throw new \Exception('INVALID_ACCESS_TOKEN');					
				}

				throw new \Exception($response->getBody());
			}
			
			throw new \Exception($body['message']);
			
		}

		$responseJson = json_decode($response->getBody(), true);

		return $responseJson;
	} 

}
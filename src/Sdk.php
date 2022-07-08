<?php

namespace Rededor\Scheduling;

class Sdk {

	private $version = null;
	private $env = null;

	private $clientId = null;
	private $accessToken = null;

	private static $envConfigs = [
		'dev' => [
			'endpoints' => [
				'auth' => 'https://api.rededor.com.br',
				'api' => 'https://api-dev.rededor.com.br/scheduling'
			]
		]
    ];

    private $modules = [];
    static private $modulesAvailable = [
        'specialities',
        'locations',
        'healthInsurances',
        'healthPlans',
        'professionals',
        'prices',
        'slots',
        'scheduling',
        'settings',
    ];

	public function __construct($version, $env) {
		$this->version = $version;
		$this->env = $env;
		$this->config = static::$envConfigs[$env];
	}

	public function authenticate($clientId, $clientSecret) {
        
        $auth = new \Rededor\Scheduling\Modules\Auth(
        	$this->config['endpoints']['auth']
        );

        $this->clientId = $clientId;

        $response = $auth->login(
        	[
        		'id' => $clientId,
        		'secret' => $clientSecret
        	]
        );

        if (empty($response['access_token'])) {
            throw new \Exception('Invalid Credentials');
        }

        $this->accessToken = $response['access_token'];

        return $this;
    }

    public function __get($name)
    {   
        if (!in_array($name, static::$modulesAvailable)) {
            throw new \Exception('Module ' . $name . ' does not exist.');
        }

        // if (empty($this->accessToken)) {
        // 	throw new \Exception('Must authenticate first.');	
        // }

        if (empty($this->modules[$name])) {
            $class = '\\Rededor\\Scheduling\\Modules\\' . ucfirst($name);

            $this->modules[$name] = new $class(
            	$this->config['endpoints']['api'] . '/' . $this->version, 
            	$this->clientId,
            	$this->accessToken
            );
        }

        return $this->modules[$name];
    }

}
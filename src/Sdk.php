<?php

namespace Rededor\Scheduling;

class Sdk {

	private $version = null;
	private $env = null;

	private $clientId = null;
    private $clientSecret = null;
	private $accessToken = null;
    private $tokenSaveMethod = null;
    private $tokenLoadMethod = null;

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

    public function getAccessToken() {
        return $this->accessToken;
    }

    public function setClientId($clientId) {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientId() {
        return $this->clientId;
    }

    public function setTokenSaveMethod($fn) {
        $this->tokenSaveMethod = $fn;

        return $this;
    }

    public function setTokenLoadMethod($fn) {
        $this->tokenLoadMethod = $fn;

        return $this;
    }

    public function recoverToken() {
        $this->authenticate();
    }

    public function setClientSecret($clientSecret) {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;

        return $this;
    }

	public function authenticate($clientId = null, $clientSecret = null) {
        
        if ($clientId !== null) {
            $this->clientId = $clientId;
        }  

        if ($clientSecret !== null) {
            $this->clientSecret = $clientSecret;
        }

        $auth = new \Rededor\Scheduling\Modules\Auth(
            $this,
        	$this->config['endpoints']['auth']
        );

        $response = $auth->login(
        	[
        		'id' => $this->clientId,
        		'secret' => $this->clientSecret
        	]
        );

        if (empty($response['access_token'])) {
            throw new \Exception('Invalid Credentials');
        }

        $this->accessToken = $response['access_token'];

        if (!empty($this->tokenSaveMethod)) {
            $fn = $this->tokenSaveMethod;
            $fn($this->accessToken);    
        }
        

        return $this;
    }

    public function __get($name)
    {   
        if (!in_array($name, static::$modulesAvailable)) {
            throw new \Exception('Module ' . $name . ' does not exist.');
        }

        if (empty($this->clientId)) {
            throw new \Exception('Client Id must be set.');  
        }

        if (empty($this->accessToken)) {
            if (empty($this->tokenLoadMethod)) {
                throw new \Exception('Access Token must be set.');      
            }
        	$fn = $this->tokenLoadMethod;
            $accessToken = $fn();    

            if (!empty($accessToken)) {
                $this->accessToken = $accessToken;
            } else {
                if (empty($this->clientSecret)) {
                    throw new \Exception('Client Secret must be set.');  
                }

                $this->authenticate();
            }
        }



        if (empty($this->modules[$name])) {
            $class = '\\Rededor\\Scheduling\\Modules\\' . ucfirst($name);

            $this->modules[$name] = new $class(
                $this,
            	$this->config['endpoints']['api'] . '/' . $this->version            	
            );
        }

        return $this->modules[$name];
    }

}
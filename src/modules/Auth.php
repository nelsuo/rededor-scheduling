<?php 

namespace Rededor\Scheduling\Modules;

class Auth extends Generic
{
  
    /**
     * Requests a new access token based on the credentials
     */
    public function login($credentials) {
        $response = $this->_post(
        	'/oauth/access-token', 
        	[
        		'grant_type' => 'client_credentials'
        	],
        	[
        		'headers' => [
        			'Authorization' => 'Basic ' . base64_encode($credentials['id'] . ':' . $credentials['secret']),
        			'x-origin-application' => 'sensedia',
        			'x-origin-channel' => 'tuasaude'
        		]
        	]
        );

        return $response;
    }

}
<?php 

namespace Rededor\Scheduling\Modules;

class Auth extends Generic
{
  	
	protected $config = [
		'route' => 'oauth/access-token',
		'methods' => [
			'post' => [
				'parameters' => [
					'grant_type:required'
				]
			]
		]
	];

    /**
     * Requests a new access token based on the credentials
     *
     * @param      <type>  $credentials  The credentials (id, secret)
     *
     * @return     <type>  The server response.
     */
    public function login($credentials) {
        $response = $this->post(
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
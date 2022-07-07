<?php 

namespace Rededor\Scheduling\Modules;

class Specialities extends Generic
{

	/**
     * Requests a new access token based on the credentials
     */
    //@TODO: make this a generic function on the generic class.
    public function get($parameters = []) {

    	$parameters = array_filter($parameters);

    	$response = $this->_get(
        	'/specialities', 
        	$parameters
        );

        return $response;
    }

}
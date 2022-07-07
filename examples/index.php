<?php


require_once "../vendor/autoload.php";

$sdk = new Rededor\Scheduling\Sdk('v1', 'dev');

$sdk->authenticate(
	'fbe022b5-b20a-4542-b962-d6ad403b36ad',
	'b78d16a8-781a-411a-8c77-b5f7ff265b7f'
);

$results = $sdk->specialities->get([
	'description' => ''
]);
	
print_r($results);
README.


require_once "../vendor/autoload.php";

$sdk = new Rededor\Scheduling\Sdk('v1', 'dev');

$sdk->authenticate(
	'...',
	'...'
);

$results = $sdk->specialities->get([
	'description' => 'cardio'
]);
	
print_r($results);
README.

```
require_once "../vendor/autoload.php";

$sdk = new Rededor\Scheduling\Sdk('v1', 'dev');

$sdk->authenticate(
	'',
	''
);


####################################################################################

$results = $sdk->specialities->get([
	'description' => 'cardi',
 	'page' => 1,
 	'pageSize' => 10
]);

print_r($results['items'][0]);
var_dump(count($results['items']));


####################################################################################

$results = $sdk->locations->get([
 	'term' => 'Rio de Janeiro',
 	// 'neighborhood' => '', 
 	// 'city' => '', 
 	// 'name' => '',
 	'page' => 1,
 	'pageSize' => 10
]);

print_r($results['items'][0]);
var_dump(count($results['items']));


####################################################################################


$results = $sdk->healthInsurances->get([
	'name' => 'Bra',
	'page' => 1,
	'pageSize' => 10
]);

print_r(array_slice($results['items'], 0, 2));
var_dump(count($results['items']));

####################################################################################

$results = $sdk->healthPlans->get([
	'healthInsurance' => 'Bradesco',
	'healthPlan' => 'Rio ',
	'page' => 1,
	'pageSize' => 10
]);

print_r(array_slice($results['items'], 0, 2));
var_dump(count($results['items']));

####################################################################################

$results = $sdk->professionals->get([
	'name' => 'Antonio', 
	// 'gender' => '', 
	// 'crm' => '',
	'page' => 1, 
	'pageSize' => 11,
]);

print_r(array_slice($results['items'], 0, 1));
var_dump(count($results['items']));

####################################################################################

$results = $sdk->prices->get([
	'locationId' => 51, 
	'specialtyIds' => [20], 
]);

print_r($results);


####################################################################################

$date = new DateTime();
$date->add(new DateInterval('P3D'));
$initialDate = $date->format('Y-m-d') . 'T00:00:00-03:00';
$date->add(new DateInterval('P10D'));
$finalDate = $date->format('Y-m-d') . 'T00:00:00-03:00';

$results = $sdk->slots->get([
	'initialDate' => $initialDate, 
	'finalDate' => $finalDate,
	'locationId' => '51',
	'specialtyIds' => [20],
	//'professionalId',
	'healthPlanId' => 165943898,
	
]);

print_r($results[0]);
var_dump(count($results));

####################################################################################

$result = $sdk->scheduling->post([
	'specialtyId' => 20,
	'sectorId' => 52,
	'professionalId' => 196208,
	'healthPlanId' => 165943898,
	'initialDate' => '2022-07-11T14:00:00.000-03:00',
	'finalDate' => '2022-07-11T14:20:00.000-03:00',
	'patient' => [
		'name' => 'Nelson Teixeira',
		'email' => 'neteixeira@gmail.com',
		'phone' => '+55 21 985683210',
		'birthDate' => '1994-03-01',
		'gender' => 'M',
		'cpf' => '10696634767'
	]
]);

print_r($result);

```

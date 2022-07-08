<?php 
namespace Rededor\Scheduling\Modules;

class HealthInsurances extends Generic
{
	protected $config = [
		'route' => 'health-insurances',
		'methods' => [
			'get' => [
				'parameters' => [
					'page', 
					'pageSize', 
					'name'
				]
			]
		]
	];
}
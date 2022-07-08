<?php 
namespace Rededor\Scheduling\Modules;

class HealthPlans extends Generic
{
	protected $config = [
		'route' => 'health-plans',
		'methods' => [
			'get' => [
				'parameters' => [
					'page', 
					'pageSize', 
					'healthPlan', 
					'healthInsurance'
				]
			]
		]
	];
}
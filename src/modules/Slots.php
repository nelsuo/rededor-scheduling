<?php 
namespace Rededor\Scheduling\Modules;

class Slots extends Generic
{
	protected $config = [
		'route' => 'slots',
		'methods' => [
			'get' => [
				'parameters' => [
					'initialDate:required', 
					'finalDate:required', 
					'locationId:required',
					'specialtyIds:required',
					'professionalId',
					'healthPlanId'
				]
			]
		]
	];
}
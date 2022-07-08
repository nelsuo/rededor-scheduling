<?php 
namespace Rededor\Scheduling\Modules;

class Prices extends Generic
{
	protected $config = [
		'route' => 'prices',
		'methods' => [
			'get' => [
				'parameters' => [
					'locationId:required', 
					'specialtyIds:required', 
					'professionalId'
				]
			]
		]
	];
}
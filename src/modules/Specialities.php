<?php 
namespace Rededor\Scheduling\Modules;

class Specialities extends Generic
{
	protected $config = [
		'route' => 'specialities',
		'methods' => [
			'get' => [
				'parameters' => [
					'page', 
					'pageSize', 
					'description'
				]
			]
		]
	];
}
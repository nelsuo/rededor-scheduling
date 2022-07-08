<?php 
namespace Rededor\Scheduling\Modules;

class Locations extends Generic
{
	protected $config = [
		'route' => 'locations',
		'methods' => [
			'get' => [
				'parameters' => [
					'page', 
					'pageSize', 
					'term', 
					'neighborhood', 
					'city', 
					'name'
				]
			]
		]
	];
}
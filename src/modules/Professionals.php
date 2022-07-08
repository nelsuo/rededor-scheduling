<?php 
namespace Rededor\Scheduling\Modules;

class Professionals extends Generic
{
	protected $config = [
		'route' => 'professionals',
		'methods' => [
			'get' => [
				'parameters' => [
					'page', 
					'pageSize', 
					'name', 
					'gender', 
					'crm'
				]
			]
		]
	];
}
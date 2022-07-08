<?php

namespace Rededor\Scheduling\Modules;

class Settings extends Generic
{
	protected $config = [
		'route' => 'prices',
		'methods' => [
			'put' => [
				'parameters' => [
					'type:required', 
					'value:required', 
					'secret:required',
				]
			],
			'get'
		]
	];
}
<?php 
namespace Rededor\Scheduling\Modules;

class Scheduling extends Generic
{
	protected $config = [
		'route' => 'scheduling',
		'methods' => [
			'post' => [
				'parameters' => [
					'specialtyId:required', 
					'sectorId:required', 
					'healthPlanId',
					'initialDate:required',
					'finalDate:required',
					'professionalId',
					'patient.name:required',
					'patient.email:required',
					'patient.phone:required',
					'patient.birthDate:required',
					'patient.gender:required',
					'patient.cpf:required',
				]
			],
			'get' => [
				'parameters' => [
					'requisition_id:required'
				]
			],
			'delete' => [
				'parameters' => [
					'requisition_id:required'
				]
			]
		]
	];
}
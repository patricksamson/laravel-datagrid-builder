<?php

return [
	'defaults' => [
		'datagrid_class' => 'table table-condensed table-hover table-striped',
		'column_class' => 'form-control',
	],

	// Templates
	'form' => 'laravel-form-builder::form',
	'text' => 'laravel-form-builder::text',
	'textarea' => 'laravel-form-builder::textarea',
	'button' => 'laravel-form-builder::button',
	'radio' => 'laravel-form-builder::radio',
	'checkbox' => 'laravel-form-builder::checkbox',
	'select' => 'laravel-form-builder::select',
	'choice' => 'laravel-form-builder::choice',
	'repeated' => 'laravel-form-builder::repeated',
	'child_form' => 'laravel-form-builder::child_form',
	'collection' => 'laravel-form-builder::collection',
	'static' => 'laravel-form-builder::static',

	'default_namespace' => '',

	'custom_fields' => [
//        'datetime' => 'App\Forms\Fields\Datetime'
	],

];
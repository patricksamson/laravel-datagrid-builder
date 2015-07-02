<?php namespace Lykegenes\LaravelDatagridBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class DatagridBuilder extends Facade
{
	public static function getFacadeAccessor()
	{
		return 'laravel-datagrid-builder';
	}
}
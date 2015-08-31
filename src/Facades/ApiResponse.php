<?php namespace Lykegenes\LaravelDatagridBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class ApiResponse extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Lykegenes\LaravelDatagridBuilder\ApiResponse::class;
    }
}

<?php namespace Lykegenes\DatagridBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class ApiResponse extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Lykegenes\DatagridBuilder\ApiResponse::class;
    }
}

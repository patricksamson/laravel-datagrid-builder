<?php
namespace Lykegenes\DatagridBuilder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @codeCoverageIgnore
 */
class DatagridBuilder extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Lykegenes\DatagridBuilder\DatagridBuilder::class;
    }
}

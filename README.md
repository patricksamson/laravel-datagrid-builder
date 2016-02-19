# Laravel Datagrid Builder

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Code Coverage][ico-coveralls]][link-coveralls]
[![Total Downloads][ico-downloads]][link-downloads]

The package is a Laravel wrapper for [Bootstrap Table](https://github.com/wenzhixin/bootstrap-table) JS library. It allows you to quickly build reusable Datatables in your frontend and bind your API as its data source. Notable features are Sorting, Searching, Hide/Show columns, Pagination, and personalized columns.

## Install

Via Composer

``` bash
composer require lykegenes/laravel-datagrid-builder
```

Then, add this to your Service Providers :

``` php
Lykegenes\DatagridBuilder\ServiceProvider::class,
```

...and this to your Aliases :

``` php
'DatagridBuilder' => Lykegenes\DatagridBuilder\Facades\DatagridBuilder::class,
```

Optionally, you can publish and edit the configuration file :

``` bash
php artisan vendor:publish --provider="Lykegenes\DatagridBuilder\ServiceProvider" --tag=config
```

## Usage

See the Docs.

## Credits

- [Patrick Samson][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lykegenes/laravel-datagrid-builder.svg
[ico-license]: https://img.shields.io/packagist/l/lykegenes/laravel-datagrid-builder.svg
[ico-travis]: https://img.shields.io/travis/Lykegenes/laravel-datagrid-builder/master.svg
[ico-coveralls]: https://img.shields.io/coveralls/Lykegenes/laravel-datagrid-builder.svg
[ico-downloads]: https://img.shields.io/packagist/dt/lykegenes/laravel-datagrid-builder.svg

[link-packagist]: https://packagist.org/packages/lykegenes/laravel-datagrid-builder
[link-travis]: https://travis-ci.org/Lykegenes/laravel-datagrid-builder
[link-coveralls]: https://coveralls.io/github/Lykegenes/laravel-datagrid-builder
[link-downloads]: https://packagist.org/packages/lykegenes/laravel-datagrid-builder
[link-author]: https://github.com/lykegenes
[link-contributors]: ../../contributors

<?php namespace Lykegenes\LaravelDatagridBuilder;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class DatagridBuilderServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//$this->commands('Lykegenes\LaravelDatagridBuilder\Console\DatagridMakeCommand');

		$this->mergeConfigFrom(
			__DIR__ . '/../../config/config.php',
			'laravel-datagrid-builder'
		);

		$this->registerDatagridHelper();

		$this->app->bindShared('laravel-datagrid-builder', function ($app)
		{

			return new DatagridBuilder($app, $app['laravel-datagrid-helper']);
		});
	}

	protected function registerDatagridHelper()
	{
		$this->app->bindShared('laravel-datagrid-helper', function ($app)
		{

			$configuration = $app['config']->get('laravel-datagrid-builder');

			return new DatagridHelper($app['view'], $app['request'], $configuration);
		});

		$this->app->alias('laravel-datagrid-helper', 'Lykegenes\LaravelDatagridBuilder\DatagridHelper');
	}

	public function boot()
	{
		$this->loadViewsFrom(__DIR__ . '/../../views', 'laravel-datagrid-builder');

		$this->publishes([
			__DIR__ . '/../../views' => base_path('resources/views/vendor/laravel-datagrid-builder'),
			__DIR__ . '/../../config/config.php' => config_path('laravel-datagrid-builder.php'),
		]);
	}

	/**
	 * @return string[]
	 */
	public function provides()
	{
		return ['laravel-datagrid-builder'];
	}

	/**
	 * Check if an alias already exists in the IOC
	 * @param $alias
	 * @return bool
	 */
	private function aliasExists($alias)
	{
		return array_key_exists($alias, AliasLoader::getInstance()->getAliases());
	}

}
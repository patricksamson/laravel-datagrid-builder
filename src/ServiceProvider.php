<?php namespace Lykegenes\DatagridBuilder;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //$this->commands('Lykegenes\DatagridBuilder\Console\DatagridMakeCommand');
        $configPath = __DIR__ . '/../config/config.php';
        $this->mergeConfigFrom($configPath, 'datagrid-builder');

        $this->registerDatagridHelper();

        $this->app->bindShared('datagrid-builder', function ($app) {

            return new DatagridBuilder($app, $app['datagrid-helper']);
        });
    }

    protected function registerDatagridHelper()
    {
        $this->app->bindShared('datagrid-helper', function ($app) {

            $configuration = $app['config']->get('datagrid-builder');

            return new DatagridHelper($app['view'], $app['request'], $configuration);
        });

        $this->app->alias('datagrid-helper', 'Lykegenes\DatagridBuilder\DatagridHelper');
    }

    public function boot()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $viewsPath  = __DIR__ . '/../views';

        $this->loadViewsFrom($viewsPath, 'datagrid-builder');

        $this->publishes([$configPath => $this->getConfigPath()], 'config');
        $this->publishes([$viewsPath => $this->getViewsPath()], 'views');
    }

    /**
     * @return string[]
     */
    public function provides()
    {
        return ['datagrid-builder'];
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('datagrid-builder.php');
    }

    /**
     * Get the views path
     *
     * @return string
     */
    protected function getViewsPath()
    {
        return base_path('resources/views/vendor/datagrid-builder');
    }

}

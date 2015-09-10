<?php

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Factory;
use Lykegenes\DatagridBuilder\Datagrid;
use Lykegenes\DatagridBuilder\DatagridBuilder;
use Lykegenes\DatagridBuilder\DatagridHelper;
use Orchestra\Testbench\TestCase;

abstract class DatagridBuilderTestCase extends TestCase
{

    /**
     * @var \Illuminate\View\Factory
     */
    protected $view;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var DatagridHelper
     */
    protected $datagridHelper;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var DatagridBuilder
     */
    protected $datagridBuilder;

    /**
     * @var Datagrid
     */
    protected $plainDatagrid;

    public function setUp()
    {
        parent::setUp();

        $this->view    = $this->app['view'];
        $this->request = $this->app['request'];
        $this->request->setSession($this->app['session.store']);
        $this->config = include __DIR__ . '/../config/config.php';

        $this->datagridHelper  = new DatagridHelper($this->view, $this->request, $this->config);
        $this->datagridBuilder = new DatagridBuilder($this->app, $this->datagridHelper);

        $this->plainDatagrid = $this->datagridBuilder->plain();
    }

    public function tearDown()
    {
        Mockery::close();
        $this->view            = null;
        $this->request         = null;
        $this->container       = null;
        $this->config          = null;
        $this->datagridHelper  = null;
        $this->datagridBuilder = null;
        $this->plainDatagrid   = null;
    }

    protected function getPackageProviders($app)
    {
        return ['Lykegenes\DatagridBuilder\ServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Acme' => 'Lykegenes\DatagridBuilder\Facades\DatagridBuilder',
        ];
    }

}

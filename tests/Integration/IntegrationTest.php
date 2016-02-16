<?php

namespace Lykegenes\DatagridBuilder\TestCase;

class IntegrationTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @var Lykegenes\DatagridBuilder\DatagridBuilder
     */
    protected $builder;

    public function setUp()
    {
        parent::setUp();

        $this->builder = $this->app->make(\Lykegenes\DatagridBuilder\DatagridBuilder::class);
    }
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Lykegenes\DatagridBuilder\ServiceProvider',
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.locale', 'en');

        $app['router']->get('plainDatagrid', function () {
                return datagrid($this->builder->plain());
        });
    }

    /** @test */
    public function testGetLocaleRoute()
    {
        $this->visit('plainDatagrid')
            ->see('<table') // The Html tag
            ->see('data-toggle="table"'); // The trigger for the bootstrap table library (JS)
    }
}

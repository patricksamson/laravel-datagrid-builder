<?php

namespace Lykegenes\DatagridBuilder\TestCase;

class IntegrationTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @var \Lykegenes\DatagridBuilder\DatagridBuilder
     */
    protected $builder;

    /**
     * @var \Lykegenes\DatagridBuilder\Datagrid
     */
    protected $plainDatagrid;

    /**
     * @var \Lykegenes\DatagridBuilder\Datagrid
     */
    protected $classDatagrid;

    public function setUp()
    {
        parent::setUp();

        $this->builder = $this->app->make(\Lykegenes\DatagridBuilder\DatagridBuilder::class);

        $this->plainDatagrid = $this->builder->plain();
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
        $app['router']->get('plainDatagrid', function () {
            return datagrid($this->plainDatagrid);
        });

        $app['router']->get('classDatagrid', function () {
            return datagrid($this->classDatagrid);
        });
    }

    /** @test */
    public function testGetBasicEmptyDatagrid()
    {
        $this->visit('plainDatagrid')
            ->see('<table') // The Html tag
            ->see('data-toggle="table"') // The trigger for the bootstrap table library (JS)
            ->dontSee('<th>'); // There shouldn't be any columns
    }

    /** @test */
    public function testGetDatagridWithOneColumn()
    {
        $this->plainDatagrid->add('column');

        $this->visit('plainDatagrid')
            ->see('>Column</th>') // The human-friendly and visible column name
            ->see('data-field="column"'); // The attribute to look for in the JSON from the API
    }

    /** @test */
    public function testGetDatagridWithMultipleColumns()
    {
        $this->plainDatagrid->add('column')
            ->add('otherColumn');

        $this->visit('plainDatagrid')
            ->see('data-field="column"')
            ->see('data-field="otherColumn"');
    }

    /** @test */
    public function testGetDatagridFromClass()
    {
        $this->classDatagrid = $this->builder->create(\Lykegenes\DatagridBuilder\TestCase\DummyDatagrid::class);

        $this->assertInstanceOf(\Lykegenes\DatagridBuilder\Datagrid::class, $this->classDatagrid);

        $this->visit('classDatagrid')
            ->see('data-field="column"')
            ->see('data-field="otherColumn"');

        $this->expectException(\InvalidArgumentException::class);
        $this->classDatagrid = $this->builder->create('missing class');
    }

    /** @test */
    public function testDatagridViewFallback()
    {
        $this->plainDatagrid->setDatagridOption('view', 'missingview');

        $this->plainDatagrid->add('column')
            ->add('otherColumn');

        $this->visit('plainDatagrid')
            ->see('data-field="column"')
            ->see('data-field="otherColumn"');
    }
}

class DummyDatagrid extends \Lykegenes\DatagridBuilder\Datagrid
{
    public function buildDatagrid()
    {
        $this->add('column')
             ->add('otherColumn');
    }
}

<?php

namespace Lykegenes\DatagridBuilder\TestCase;

class DatagridColumnTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @var Lykegenes\DatagridBuilder\DatagridBuilder
     */
    protected $builder;

    /**
     * @var Lykegenes\DatagridBuilder\Datagrid
     */
    protected $plainDatagrid;

    /**
     * @var Lykegenes\DatagridBuilder\DatagridColumn
     */
    protected $column;

    public function setUp()
    {
        parent::setUp();

        $this->builder = $this->app->make(\Lykegenes\DatagridBuilder\DatagridBuilder::class);

        $this->plainDatagrid = $this->builder->plain();
        $this->plainDatagrid->add('column');
        $this->column = $this->plainDatagrid->getColumn('column');
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

    /** @test */
    public function it_sets_column_name()
    {
        $this->assertEquals('column', $this->column->getName());
        $this->column->setName('new name');
        $this->assertEquals('new name', $this->column->getName());
    }

    /** @test */
    public function it_gets_parent_datagrid()
    {
        $this->assertInstanceOf(\Lykegenes\DatagridBuilder\Datagrid::class, $this->column->getParent());
        $this->assertEquals($this->plainDatagrid, $this->column->getParent());
    }

    /** @test */
    public function it_can_set_column_options()
    {
        $this->assertEquals(null, $this->column->getOption('test'));
        $this->column->setOption('test', 'value');
        $this->assertEquals('value', $this->column->getOption('test'));
        $this->assertEquals('value', $this->column->getOptions()['test']);

        $this->column->setOption('test', 'overwrite');
        $this->assertEquals('overwrite', $this->column->getOption('test'));
        $this->assertEquals('overwrite', $this->column->getOptions()['test']);
    }
}

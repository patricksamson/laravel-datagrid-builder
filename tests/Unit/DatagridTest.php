<?php

namespace Lykegenes\DatagridBuilder\TestCase;

class DatagridTest extends \Orchestra\Testbench\TestCase
{
    /**
     * @var Lykegenes\DatagridBuilder\DatagridBuilder
     */
    protected $builder;
    /**
     * @var Lykegenes\DatagridBuilder\Datagrid
     */
    protected $plainDatagrid;

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

    /** @test */
    public function testAddingColumns()
    {
        $this->assertFalse($this->plainDatagrid->has('firstColumn'));
        $this->plainDatagrid->add('firstColumn');
        $this->assertTrue($this->plainDatagrid->has('firstColumn'));

        $columns = $this->plainDatagrid->getColumns();
        $this->assertArrayHasKey('firstColumn', $columns);
        $this->assertInstanceOf(\Lykegenes\DatagridBuilder\DatagridColumn::class, $columns['firstColumn']);
        $this->assertEquals('firstColumn', $columns['firstColumn']->getName());
        $this->assertEquals($columns['firstColumn'], $this->plainDatagrid->firstColumn);
    }

    /** @test */
    public function testDontAddDuplicates()
    {
        $this->plainDatagrid->add('firstColumn');
        $this->expectException(\InvalidArgumentException::class);
        $this->plainDatagrid->add('firstColumn');
    }
}

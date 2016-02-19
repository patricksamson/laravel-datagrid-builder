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
    public function it_can_add_column()
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
    public function it_throws_exception_when_adding_duplicate_column()
    {
        $this->plainDatagrid->add('firstColumn');
        $this->expectException(\InvalidArgumentException::class);
        $this->plainDatagrid->add('firstColumn');
    }

    /** @test */
    public function it_can_remove_a_column_from_datagrid()
    {
        $this->plainDatagrid->add('firstColumn');
        $this->assertTrue($this->plainDatagrid->has('firstColumn'));

        $this->plainDatagrid->remove('firstColumn');
        $this->assertFalse($this->plainDatagrid->has('firstColumn'));

        $this->expectException(\InvalidArgumentException::class);
        $this->plainDatagrid->remove('firstColumn');
    }

    /** @test */
    public function it_can_set_datagrid_name()
    {
        $this->plainDatagrid->setName('some name');
        $this->assertEquals('some name', $this->plainDatagrid->getName());
    }

    /** @test */
    public function it_validates_columns_names()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->plainDatagrid->add(null);
        $this->assertFalse($this->plainDatagrid->has(null));

        $this->plainDatagrid->add('');
        $this->assertFalse($this->plainDatagrid->has(''));
    }

    /** @test */
    public function it_modifies_existing_columns()
    {
        // add column if it doesn't exist already
        $this->plainDatagrid->modify('firstColumn');
        $this->assertTrue($this->plainDatagrid->has('firstColumn'));

        // modify the column options
        $this->plainDatagrid->modify('firstColumn', ['attr' => ['test' => 'option value']]);
        $this->assertEquals('option value', $this->plainDatagrid->getColumn('firstColumn')->getOption('attr.test'));

        // overwrite the column options
        $this->plainDatagrid->modify('firstColumn', ['attr' => ['other' => 'other value']], true);
        $this->assertEquals(null, $this->plainDatagrid->getColumn('firstColumn')->getOption('attr.test'));
        $this->assertEquals('other value', $this->plainDatagrid->getColumn('firstColumn')->getOption('attr.other'));
    }
}

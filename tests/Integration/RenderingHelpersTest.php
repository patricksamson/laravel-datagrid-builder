<?php

namespace Lykegenes\DatagridBuilder\TestCase;

class RenderingHelpersTest extends \Orchestra\Testbench\TestCase
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
    public function testDatagridUntilAndRest()
    {
        $this->plainDatagrid->add('firstColumn')
            ->addAfter('firstColumn', 'otherColumn');

        $this->app['router']->get('until', function () {
            return datagrid_until($this->plainDatagrid, 'firstColumn');
        });

        $this->visit('until')
            ->dontSee('<table')
            ->see('data-field="firstColumn"')
            ->dontSee('data-field="otherColumn"')
            ->dontSee('</table>');

        // now test the rest of the datagrid
        $this->app['router']->get('rest', function () {
            return datagrid_rest($this->plainDatagrid);
        });

        $this->visit('rest')
            ->dontSee('<table')
            ->dontSee('data-field="firstColumn"')
            ->see('data-field="otherColumn"')
            ->dontSee('</table>');
    }

    /** @test */
    public function testDatagridStart()
    {
        $this->plainDatagrid->add('firstColumn');

        $this->app['router']->get('start', function () {
            return datagrid_start($this->plainDatagrid);
        });

        $this->visit('start')
            ->see('<table')
            ->dontSee('data-field="firstColumn"')
            ->dontSee('</table>');
    }

    /** @test */
    public function testDatagridEnd()
    {
        $this->plainDatagrid->add('firstColumn');

        $this->app['router']->get('end', function () {
            return datagrid_end($this->plainDatagrid, false);
        });

        $this->visit('end')
            ->dontSee('<table')
            ->dontSee('data-field="firstColumn"')
            ->see('</table>');
    }
}

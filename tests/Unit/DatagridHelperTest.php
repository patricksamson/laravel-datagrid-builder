<?php

namespace Lykegenes\DatagridBuilder\TestCase;

class DatagridHelperTest extends \Orchestra\Testbench\TestCase
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
     * @var Lykegenes\DatagridBuilder\DatagridHelper
     */
    protected $helper;

    public function setUp()
    {
        parent::setUp();

        $this->builder = $this->app->make(\Lykegenes\DatagridBuilder\DatagridBuilder::class);

        $this->plainDatagrid = $this->builder->plain();
        $this->helper = $this->plainDatagrid->getDatagridHelper();
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
    public function it_formats_label()
    {
        $this->assertEquals(null, $this->helper->formatLabel(null));
        $this->assertEquals('Test', $this->helper->formatLabel('test'));
        $this->assertEquals('Other Test', $this->helper->formatLabel('other_test'));
        $this->assertEquals('Other Test', $this->helper->formatLabel('Other Test'));
    }

    /** @test */
    public function it_merges_options()
    {
        // append
        $first = ['option' => 'value'];
        $second = ['other option' => 'other value'];
        $this->assertEquals($this->helper->mergeOptions($first, $second), [
            'option' => 'value',
            'other option' => 'other value',
        ]);

        // overwrite
        $first = ['option' => 'value'];
        $second = ['option' => 'new value'];
        $this->assertEquals($this->helper->mergeOptions($first, $second), [
            'option' => 'new value',
        ]);
    }
}

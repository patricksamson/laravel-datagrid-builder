<?php

use Lykegenes\DatagridBuilder\Datagrid;

class DatagridBuilderTest extends DatagridBuilderTestCase
{

    /** @test */
    public function it_creates_plain_datagrid_and_sets_options_on_it()
    {
        $options = [
            'method' => 'PUT',
            'url'    => '/some/url/1',
        ];
        $plainDatagrid = $this->datagridBuilder->plain($options);
        $this->assertEquals('PUT', $plainDatagrid->getMethod());
        $this->assertEquals('/some/url/1', $plainDatagrid->getUrl());
        $this->assertNull($plainDatagrid->buildDatagrid());
    }

    /** @test */
    public function it_creates_custom_datagrid_and_sets_options_on_it()
    {
        $options = [
            'method' => 'POST',
            'url'    => '/posts',
            'data'   => ['dummy_choices' => [1 => 'choice_1', 2 => 'choice_2']],
        ];
        $customDatagrid = $this->datagridBuilder->create('CustomDummyDatagrid', $options);
        $this->assertEquals('POST', $customDatagrid->getMethod());
        $this->assertEquals($this->request, $customDatagrid->getRequest());
        $this->assertEquals('/posts', $customDatagrid->getUrl());
        $this->assertEquals([1 => 'choice_1', 2 => 'choice_2'], $customDatagrid->getData('dummy_choices'));
        $this->assertInstanceOf('Lykegenes\\DatagridBuilder\\Datagrid', $customDatagrid);
        $this->assertArrayHasKey('title', $customDatagrid->getColumns());
        $this->assertArrayHasKey('body', $customDatagrid->getColumns());
    }

    /** @test */
    public function it_can_set_datagrid_helper_once_and_call_build_datagrid()
    {
        $datagrid = $this->datagridBuilder->create('CustomDummyDatagrid');
        $this->assertEquals($this->datagridHelper, $datagrid->getDatagridHelper());
        $this->assertEquals($this->datagridBuilder, $datagrid->getDatagridBuilder());
        $this->assertArrayHasKey('title', $datagrid->getColumns());
        $this->assertArrayHasKey('body', $datagrid->getColumns());
    }
}

class CustomDummyDatagrid extends Datagrid
{
    public function buildDatagrid()
    {
        $this->add('title')
             ->add('body');
    }
}

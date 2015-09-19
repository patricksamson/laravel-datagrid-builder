<?php

use Lykegenes\DatagridBuilder\DatagridColumn;

class DatagridTest extends DatagridBuilderTestCase
{
    /** @test */
    public function it_adds_columns()
    {
        $this->plainDatagrid
             ->add('name')
             ->add('description')
             ->add('address')
             ->add('remember');
        $this->assertEquals(4, count($this->plainDatagrid->getColumns()));
        $this->assertTrue($this->plainDatagrid->has('name'));
        $this->assertFalse($this->plainDatagrid->has('body'));
        // Accessed with magic methods
        $this->assertEquals($this->plainDatagrid->name, $this->plainDatagrid->getColumn('name'));
        $this->assertInstanceOf(
            'Lykegenes\DatagridBuilder\DatagridColumn',
            $this->plainDatagrid->getColumn('name')
        );
        $this->assertInstanceOf(
            'Lykegenes\DatagridBuilder\DatagridColumn',
            $this->plainDatagrid->getColumn('description')
        );
        $this->assertInstanceOf(
            'Lykegenes\DatagridBuilder\DatagridColumn',
            $this->plainDatagrid->getColumn('remember')
        );
        $this->assertInstanceOf(
            'Lykegenes\DatagridBuilder\DatagridColumn',
            $this->plainDatagrid->getColumn('address')
        );
    }

    /** @test */
    public function it_adds_after_some_column()
    {
        $this->plainDatagrid
             ->add('name')
             ->add('description');
        $descIndexBefore = array_search(
            'description',
            array_keys($this->plainDatagrid->getColumns())
        );
        $this->assertEquals(1, $descIndexBefore);
        $this->assertNull($this->plainDatagrid->address);
        $this->plainDatagrid->addAfter('name', 'address');
        $descIndexAfter = array_search(
            'description',
            array_keys($this->plainDatagrid->getColumns())
        );
        $addressIndex = array_search(
            'address',
            array_keys($this->plainDatagrid->getColumns())
        );
        $this->assertEquals(2, $descIndexAfter);
        $this->assertEquals(1, $addressIndex);
        $this->assertInstanceOf(
            'Lykegenes\DatagridBuilder\DatagridColumn',
            $this->plainDatagrid->address
        );
    }

    /** @test */
    public function it_adds_before_some_column()
    {
        $this->plainDatagrid
             ->add('name')
             ->add('description');
        $descIndexBefore = array_search(
            'description',
            array_keys($this->plainDatagrid->getColumns())
        );
        $this->assertEquals(1, $descIndexBefore);
        $this->assertNull($this->plainDatagrid->address);
        $this->plainDatagrid->addBefore('name', 'address');
        $descIndexAfter = array_search(
            'description',
            array_keys($this->plainDatagrid->getColumns())
        );
        $addressIndex = array_search(
            'address',
            array_keys($this->plainDatagrid->getColumns())
        );
        $this->assertEquals(2, $descIndexAfter);
        $this->assertEquals(0, $addressIndex);
        $this->assertInstanceOf(
            'Lykegenes\DatagridBuilder\DatagridColumn',
            $this->plainDatagrid->address
        );
    }

    /** @test */
    public function it_can_remove_existing_columns_from_datagrid_object()
    {
        $this->plainDatagrid
             ->add('name')
             ->add('description')
             ->add('remember');
        $this->assertEquals(3, count($this->plainDatagrid->getColumns()));
        $this->assertTrue($this->plainDatagrid->has('name'));
        $this->plainDatagrid->remove('name');
        $this->assertEquals(2, count($this->plainDatagrid->getColumns()));
        $this->assertFalse($this->plainDatagrid->has('name'));
    }

    /** @test */
    public function it_can_modify_existing_columns()
    {
        $this->plainDatagrid
             ->add('name')
             ->add('description', [
                 'attr' => ['placeholder' => 'Enter text here...'],
             ])
             ->add('category', [
                 'choices' => [1 => 'category-1', 2 => 'category-2'],
             ]);
        // Adds new if provided name doesn't exist
        $this->plainDatagrid->modify('remember');
        $this->assertEquals(
            ['placeholder' => 'Enter text here...', 'class' => 'form-control'],
            $this->plainDatagrid->description->getOption('attr')
        );
        $this->plainDatagrid->modify('description', [
            'attr' => ['class' => 'modified-input'],
        ]);
        $this->assertEquals(
            ['placeholder' => 'Enter text here...', 'class' => 'modified-input'],
            $this->plainDatagrid->description->getOption('attr')
        );
        // Check if complete option ovewrite work
        $this->assertEquals(
            [1 => 'category-1', 2 => 'category-2'],
            $this->plainDatagrid->category->getOption('choices')
        );
        $this->assertArrayNotHasKey('expanded', $this->plainDatagrid->category->getOptions());
        $this->plainDatagrid->modify('category', [
            'expanded' => true,
        ], true);
        $this->assertNotEquals(
            [1 => 'category-1', 2 => 'category-2'],
            $this->plainDatagrid->category->getOption('choices')
        );
        $this->assertTrue($this->plainDatagrid->category->getOption('expanded'));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_exception_when_removing_nonexisting_column()
    {
        $this->plainDatagrid->add('name');
        $this->plainDatagrid->remove('nonexisting');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_prevents_adding_columns_with_same_name()
    {
        $this->plainDatagrid->add('name')->add('name');
    }

    /** @test */
    public function it_throws_InvalidArgumentException_on_non_existing_property()
    {
        $exceptionThrown = false;
        $this->plainDatagrid
             ->add('name')
             ->add('description');
        try {
            $this->plainDatagrid->nonexisting;
        } catch (\InvalidArgumentException $e) {
            $exceptionThrown = true;
        }
        try {
            $this->plainDatagrid->getColumn('nonexisting');
        } catch (\InvalidArgumentException $e) {
            $exceptionThrown = true;
        }
        if ($exceptionThrown) {
            return;
        }
        $this->fail('Exception was not thrown for non existing field.');
    }

    /** @test */
    public function it_can_set_datagrid_options_with_array_of_options()
    {
        $options = [
            'method'     => 'POST',
            'url'        => '/url/1',
            'class'      => 'datagrid-container',
            'ajaxParams' => ['sort' => 'name'],
        ];
        $this->plainDatagrid->setDatagridOptions($options);

        $this->assertEquals('POST', $this->plainDatagrid->getMethod());
        $this->assertEquals('/url/1', $this->plainDatagrid->getUrl());
        $this->assertEquals(['sort' => 'name'], $this->plainDatagrid->getAjaxParams());
    }

    /** @test */
    public function it_can_set_datagrid_options_with_setters()
    {
        $this->plainDatagrid->setMethod('DELETE');
        $this->plainDatagrid->setUrl('/posts/all');
        $this->plainDatagrid->setName('test_name');
        $this->plainDatagrid->setAjaxParams(['search' => 'name']);

        $this->assertEquals('DELETE', $this->plainDatagrid->getMethod());
        $this->assertEquals('/posts/all', $this->plainDatagrid->getUrl());
        $this->assertEquals('test_name', $this->plainDatagrid->getName());
        $this->assertEquals(['search' => 'name'], $this->plainDatagrid->getAjaxParams());
    }

    /** @test */
    public function it_renders_the_datagrid()
    {
        $options = [
            'method' => 'POST',
            'url'    => '/someurl',
            'class'  => 'has-error',
        ];
        $this->plainDatagrid->renderDatagrid($options, true, true, true);
    }

    /** @test */
    public function it_renders_rest_of_the_datagrid()
    {
        $options = [
            'method' => 'GET',
            'url'    => '/some/url/10',
        ];
        $columns = [
            new DatagridColumn('name', $this->plainDatagrid),
            new DatagridColumn('email', $this->plainDatagrid),
        ];
        $this->plainDatagrid->setDatagridOptions($options);
        $this->plainDatagrid
             ->add('gender')
             ->add('name')
             ->add('email');
        $this->plainDatagrid->gender->render();
        $this->plainDatagrid->renderRest();
    }

    /** @test */
    public function it_renders_rest_of_the_datagrid_until_specified_datagrid()
    {
        $options = [
            'method' => 'GET',
            'url'    => '/some/url/10',
        ];
        $columns = [
            new DatagridColumn('name', $this->plainDatagrid),
            new DatagridColumn('email', $this->plainDatagrid),
        ];
        $this->plainDatagrid->setDatagridOptions($options);
        $this->plainDatagrid
             ->add('gender')
             ->add('name')
             ->add('email')
             ->add('address');
        $this->plainDatagrid->gender->render();
        $this->plainDatagrid->renderUntil('email');
        $this->assertEquals($this->plainDatagrid->address->isRendered(), false);
    }

    /** @test */
    public function it_creates_named_datagrid()
    {
        $this->plainDatagrid
             ->add('name')
             ->add('address');
        $this->assertEquals('name', $this->plainDatagrid->getColumn('name')->getName());
        $this->assertEquals('address', $this->plainDatagrid->getColumn('address')->getName());
        $this->plainDatagrid->setName('test_name');
        $this->plainDatagrid->renderDatagrid();
        $this->assertEquals('test_name[name]', $this->plainDatagrid->getColumn('name')->getName());
        $this->assertEquals('test_name[address]', $this->plainDatagrid->getColumn('address')->getName());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_throws_exception_when_adding_column_with_invalid_name()
    {
        $this->plainDatagrid->add('');
    }

    /** @test */
    public function it_can_compose_another_datagrids_columns_into_itself()
    {
        $datagrid       = $this->datagridBuilder->plain();
        $customDatagrid = $this->datagridBuilder->create('CustomDummyDatagrid');
        $datagrid
            ->add('name')
            ->compose($customDatagrid)
        ;
        $this->assertEquals($datagrid, $datagrid->name->getParent());
        $this->assertEquals(3, count($datagrid->getColumns()));
        $this->assertEquals(true, $datagrid->has('title'));
        $this->assertEquals('title', $datagrid->title->getName());
        $this->assertEquals('title', $datagrid->title->getRealName());
    }

}

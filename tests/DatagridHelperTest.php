<?php

class LocaleSwitcherTest extends DatagridBuilderTestCase
{

    /** @test */
    public function it_sets_constructor_dependencies_to_properties()
    {
        $this->assertEquals($this->view, $this->datagridHelper->getView());
        $this->assertEquals($this->request, $this->datagridHelper->getRequest());
    }

    /** @test */
    public function it_merges_options_properly()
    {
        $initial = [
            'attr'       => ['class' => 'form-control'],
            'label_attr' => ['class' => 'test'],
            'selected'   => null,
        ];
        $options = [
            'attr'       => ['id' => 'form-id'],
            'label_attr' => ['class' => 'new-class'],
        ];
        $expected = [
            'attr'       => ['class' => 'form-control', 'id' => 'form-id'],
            'label_attr' => ['class' => 'new-class'],
            'selected'   => null,
        ];
        $mergedOptions = $this->datagridHelper->mergeOptions($initial, $options);
        $this->assertEquals($expected, $mergedOptions);
    }

    /** @test */
    public function it_creates_html_attributes_from_array_of_options()
    {
        $options    = ['class' => 'form-control', 'data-id' => 1, 'id' => 'post'];
        $attributes = $this->datagridHelper->prepareAttributes($options);
        $this->assertEquals(
            'class="form-control" data-id="1" id="post" ',
            $attributes
        );
    }
}

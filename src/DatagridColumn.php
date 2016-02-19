<?php

namespace Lykegenes\DatagridBuilder;

/**
 * Class FormField.
 */
class DatagridColumn
{
    /**
     * Name of the column.
     *
     * @var
     */
    protected $name;

    /**
     * All options for the column.
     *
     * @var
     */
    protected $options = [
        'view' => 'datagrid-builder::column',
        'attr' => [],
    ];

    /**
     * Is column rendered.
     *
     * @var bool
     */
    protected $rendered = false;

    /**
     * @var Datagrid
     */
    protected $parent;

    /**
     * @var DatagridHelper
     */
    protected $datagridHelper;

    /**
     * @param             $name
     * @param             $type
     * @param Datagrid    $parent
     * @param array       $options
     */
    public function __construct($name, Datagrid $parent, array $options = [])
    {
        $this->name = $name;
        $this->parent = $parent;
        $this->datagridHelper = $this->parent->getDatagridHelper();
        $this->setDefaultOptions($options);
    }

    /**
     * @param array $options
     * @param bool  $showColumn
     * @return string
     */
    public function render(array $options = [], $showColumn = true)
    {
        if ($showColumn) {
            $this->rendered = true;
        }

        $options = $this->prepareOptions($options);

        return $this->datagridHelper->getView()->make(
            'datagrid-builder::column',
            [
                'name' => $this->name,
                'options' => $options,
                'colAttrs' => $options['colAttrs'],
                'showColumn' => $showColumn,
            ]
        )->render();
    }

    /**
     * Prepare options for rendering.
     *
     * @param array $options
     * @return array
     */
    protected function prepareOptions(array $options = [])
    {
        $options = $this->datagridHelper->mergeOptions($this->options, $options);

        $options['attr']['data-field'] = $this->name;
        $options['attr']['data-title'] = $this->options['label'];

        $options['colAttrs'] = $this->datagridHelper->prepareAttributes($options['attr']);

        return $options;
    }

    /**
     * Get name of the column.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name of the column.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get column options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get single option from options array. Can be used with dot notation ('attr.class').
     *
     * @param        $option
     * @param string $default
     *
     * @return mixed
     */
    public function getOption($option, $default = null)
    {
        return array_get($this->options, $option, $default);
    }

    /**
     * Set column options.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $this->prepareOptions($options);

        return $this;
    }

    /**
     * Set single option on the column.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setOption($name, $value)
    {
        array_set($this->options, $name, $value);

        return $this;
    }

    /**
     * @return Datagrid
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Check if the column is rendered.
     *
     * @return bool
     */
    public function isRendered()
    {
        return $this->rendered;
    }

    /**
     * Default options for column.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return [];
    }

    /**
     * Defaults used across all columns.
     *
     * @return array
     */
    private function allDefaults()
    {
        return [
            'attr' => $this->datagridHelper->getConfig('column_defaults.attr'),
            'label' => $this->datagridHelper->formatLabel($this->name),
            'view' => $this->datagridHelper->getConfig('column_defaults.view'),
        ];
    }

    /**
     * Merge all defaults with column specific defaults and set template if passed.
     *
     * @param array $options
     */
    protected function setDefaultOptions(array $options = [])
    {
        $this->options = $this->datagridHelper->mergeOptions($this->allDefaults(), $this->getDefaults());
        $this->options = $this->prepareOptions($options);
    }
}

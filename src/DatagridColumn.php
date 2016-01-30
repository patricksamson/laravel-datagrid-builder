<?php

namespace Lykegenes\DatagridBuilder;

/**
 * Class DatagridColumn.
 */
class DatagridColumn
{
    /**
     * Name of the column.
     *
     * @var string
     */
    protected $name;

    /**
     * All options for the column.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Is column rendered.
     *
     * @var bool
     */
    protected $rendered = false;

    /**
     * @var Datagrid
     */
    protected $parentDatagrid;

    /**
     * @var DatagridHelper
     */
    protected $datagridHelper;

    /**
     * @param          $name
     * @param          $type
     * @param Datagrid $parent
     * @param array    $options
     */
    public function __construct($name, Datagrid $parent, array $options = [])
    {
        $this->name = $name;
        $this->parentDatagrid = $parent;
        $this->datagridHelper = $this->parentDatagrid->getDatagridHelper();
        $this->setDefaultOptions($options);
    }

    /**
     * @param array $options
     * @param bool  $showColumn
     *
     * @return string
     */
    public function render(array $options = [], $showColumn = true)
    {
        if ($showColumn) {
            $this->rendered = true;
        }

        $options = $this->prepareOptions($options);

        return $this->datagridHelper->getView()->make(
            $options['view'],
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
     *
     * @return array
     */
    protected function prepareOptions(array $options = [])
    {
        $options['attr']['data-field'] = $this->name;

        $options = $this->datagridHelper->mergeOptions($this->options, $options);

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
     *
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
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $this->datagridHelper->mergeOptions($this->options, $options);

        return $this;
    }

    /**
     * Set single option on the column.
     *
     * @param string $name
     * @param mixed  $value
     *
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
    public function getParentDatagrid()
    {
        return $this->parentDatagrid;
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
    private function sharedDefaults()
    {
        return [
            'attr' => $this->datagridHelper->getConfig('column_defaults.attr'),
            'converter' => null,
            'formatter' => null,
            'label' => $this->datagridHelper->formatLabel($this->name),
            'order' => null,
            'searchable' => null,
            'sortable' => null,
            'view' => 'datagrid-builder::column',
            'visible' => null,
        ];
    }

    /**
     * Merge all defaults with column specific defaults and set template if passed.
     *
     * @param array $options
     */
    protected function setDefaultOptions(array $options = [])
    {
        $this->options = array_merge_recursive(
            $this->sharedDefaults(),
            $this->datagridHelper->getConfig('column_defaults'),
            $this->getDefaults(),
            $options
        );
    }
}

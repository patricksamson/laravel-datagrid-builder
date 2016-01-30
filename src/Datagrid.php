<?php

namespace Lykegenes\DatagridBuilder;

class Datagrid
{
    /**
     * All columns that are added.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * @var DatagridHelper
     */
    protected $datagridHelper;

    /**
     * Datagrid options.
     *
     * @var array
     */
    protected $datagridOptions = [
        'view' => 'datagrid-builder::datagrid',
        'attr' => [
            // Bootstrap-table
            'data-toggle' => 'table', // Activate bootstrap table without writing JavaScript.
        ],
    ];

    /**
     * Name of the parent datagrid if any.
     *
     * @var string|null
     */
    protected $name = null;

    /**
     * @var DatagridBuilder
     */
    protected $datagridBuilder;

    /**
     * List of columns to not render.
     *
     * @var array
     **/
    protected $exclude = [];

    /**
     * Are datagrid being rebuilt?
     *
     * @var bool
     */
    protected $rebuilding = false;

    /**
     * Build the datagrid.
     *
     * @return mixed
     */
    public function buildDatagrid()
    {
    }

    /**
     * Rebuild the datagrid from scratch.
     *
     * @return $this
     */
    public function rebuildDatagrid()
    {
        $this->rebuilding = true;
        // If datagrid is plain, buildForm method is empty, so we need to take
        // existing columns and add them again
        if (get_class($this) === 'Lykegenes\DatagridBuilder\Datagrid') {
            foreach ($this->columns as $name => $column) {
                $this->add($name, $column->getOptions());
            }
        } else {
            $this->buildDatagrid();
        }
        $this->rebuilding = false;

        return $this;
    }

    /**
     * Create the DatagridColumn object.
     *
     * @param string $name
     * @param array  $options
     *
     * @return DatagridColumn
     */
    protected function makeColumn($name, array $options = [])
    {
        return new DatagridColumn($name, $this, $options);
    }

    /**
     * Create a new column and add it to the datagrid.
     *
     * @param string $name
     * @param array  $options
     * @param bool   $modify
     *
     * @return $this
     */
    public function add($name, array $options = [], $modify = false)
    {
        if (! $name || trim($name) == '') {
            throw new \InvalidArgumentException(
                'Please provide valid column name for class ['.get_class($this).']'
            );
        }
        if ($this->rebuilding &&  ! $this->has($name)) {
            return $this;
        }
        $this->addColumn($this->makeColumn($name, $options), $modify);

        return $this;
    }

    /**
     * Add a DatagridColumn to the datagrid's columns.
     *
     * @param DatagridColumn $column
     *
     * @return $this
     */
    protected function addColumn(DatagridColumn $column, $modify = false)
    {
        if (! $modify &&  ! $this->rebuilding) {
            $this->preventDuplicate($column->getName());
        }
        $this->columns[$column->getName()] = $column;

        return $this;
    }

    /**
     * Add column before another column.
     *
     * @param string $name       Name of the column before which new column is added
     * @param string $columnName Column name which will be added
     * @param array  $options
     * @param bool   $modify
     *
     * @return $this
     */
    public function addBefore($name, $columnName, $options = [], $modify = false)
    {
        $offset = array_search($name, array_keys($this->columns));

        $this->insertColumnAt($offset, $columnName, $options, $modify);

        return $this;
    }

    /**
     * Add column before another column.
     *
     * @param string $name       Name of the column after which new column is added
     * @param string $columnName Column name which will be added
     * @param array  $options
     * @param bool   $modify
     *
     * @return $this
     */
    public function addAfter($name, $columnName, $options = [], $modify = false)
    {
        $offset = array_search($name, array_keys($this->columns));

        $this->insertColumnAt($offset + 1, $columnName, $options, $modify);

        return $this;
    }

    /**
     * @param string $columnName
     */
    private function insertColumnAt($offset, $columnName, $options = [], $modify = false)
    {
        $beforeColumns = array_slice($this->columns, 0, $offset);
        $afterColumns = array_slice($this->columns, $offset);

        $this->columns = $beforeColumns;

        $this->add($columnName, $options, $modify);

        $this->columns += $afterColumns;

        return $this;
    }

    /**
     * Remove column with specified name from the datagrid.
     *
     * @param $name
     *
     * @return $this
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($this->columns[$name]);

            return $this;
        }

        throw new \InvalidArgumentException('Column ['.$name.'] does not exist in '.get_class($this));
    }

    /**
     * Modify existing column. If it doesn't exist, it is added to datagrid.
     *
     * @param       $name
     * @param array $options
     * @param bool  $overwriteOptions
     *
     * @return Datagrid
     */
    public function modify($name, array $options = [], $overwriteOptions = false)
    {
        // If we don't want to overwrite options, we merge them with old options
        if ($overwriteOptions === false && $this->has($name)) {
            $options = $this->datagridHelper->mergeOptions(
                $this->getColumn($name)->getOptions(),
                $options
            );
        }

        return $this->add($name, $options, true);
    }

    /**
     * Take another datagrid and add it's columns directly to this datagrid.
     *
     * @param mixed $class   Datagrid to merge
     * @param array $options
     * @param bool  $modify
     *
     * @return $this
     */
    public function compose($class, array $options = [], $modify = false)
    {
        $options['class'] = $class;
        // If we pass a ready made datagrid just extract the columns
        if ($class instanceof self) {
            $columns = $class->getColumns();
        } elseif (is_string($class)) {
            // If its a string of a class make it the usual way
            $options['name'] = $this->name;
            $datagrid = $this->datagridBuilder->create($class, $options);
            $columns = $datagrid->getColumns();
        } else {
            throw new \InvalidArgumentException(
                "[{$class}] is invalid. Please provide either a full class name or Datagrid"
            );
        }
        foreach ($columns as $column) {
            $this->addColumn($column, $modify);
        }

        return $this;
    }

    /**
     * Render full datagrid.
     *
     * @param array $options
     * @param bool  $showStart
     * @param bool  $showColumns
     * @param bool  $showEnd
     *
     * @return string
     */
    public function renderDatagrid(array $options = [], $showStart = true, $showColumns = true, $showEnd = true)
    {
        return $this->render($options, $this->columns, $showStart, $showColumns, $showEnd);
    }

    /**
     * Render rest of the datagrid.
     *
     * @param bool $showDatagridEnd
     * @param bool $showColumns
     *
     * @return string
     */
    public function renderRest($showDatagridEnd = true, $showColumns = true)
    {
        $columns = $this->getUnrenderedColumns();

        return $this->render([], $columns, false, $showColumns, $showDatagridEnd);
    }

    /**
     * Renders the rest of the datagrid up until the specified column name.
     *
     * @param string $column_name
     * @param bool   $showDatagridEnd
     * @param bool   $showColumns
     *
     * @return string
     */
    public function renderUntil($column_name, $showDatagridEnd = true, $showColumns = true)
    {
        $columns = $this->getUnrenderedColumns();

        $i = 1;
        foreach ($columns as $key => $value) {
            if ($value->getRealName() == $column_name) {
                break;
            }
            ++$i;
        }

        $columns = array_slice($columns, 0, $i, true);

        return $this->render([], $columns, false, $showColumns, $showDatagridEnd);
    }

    /**
     * Get single column instance from datagrid object.
     *
     * @param $name
     *
     * @return DatagridColumn
     */
    public function getColumn($name)
    {
        if ($this->has($name)) {
            return $this->columns[$name];
        }

        throw new \InvalidArgumentException(
            'Column with name ['.$name.'] does not exist in class '.get_class($this)
        );
    }

    /**
     * Check if datagrid has column.
     *
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->columns);
    }

    /**
     * Get all datagrid options.
     *
     * @return array
     */
    public function getDatagridOptions()
    {
        return $this->datagridOptions;
    }

    /**
     * Get single datagrid option.
     *
     * @param string $option
     * @param $default
     *
     * @return mixed
     */
    public function getDatagridOption($option, $default = null)
    {
        return array_get($this->datagridOptions, $option, $default);
    }

    /**
     * Set single datagrid option on datagrid.
     *
     * @param string $option
     * @param mixed  $value
     *
     * @return $this
     */
    public function setDatagridOption($option, $value)
    {
        $this->datagridOptions[$option] = $value;

        return $this;
    }

    /**
     * Set datagrid options.
     *
     * @param array $datagridOptions
     *
     * @return $this
     */
    public function setDatagridOptions($datagridOptions)
    {
        $this->datagridOptions = $this->datagridHelper->mergeOptions($this->datagridOptions, $datagridOptions);

        $this->checkIfNamedDatagrid();

        return $this;
    }

    /**
     * Get datagrid http method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->datagridOptions['attr']['data-method'];
    }

    /**
     * Set datagrid http method.
     *
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->datagridOptions['attr']['data-method'] = $method;

        return $this;
    }

    /**
     * Get datagrid action url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->datagridOptions['attr']['data-url'];
    }

    /**
     * Set datagrid action url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->datagridOptions['attr']['data-url'] = $url;

        return $this;
    }

    /**
     * Get datagrid ajax request parameters.
     *
     * @return string
     */
    public function getAjaxParams()
    {
        return $this->datagridOptions['ajaxParams'];
    }

    /**
     * Set datagrid ajax request parameters.
     *
     * @param array $ajaxParams
     *
     * @return $this
     */
    public function setAjaxParams($ajaxParams)
    {
        $this->datagridOptions['ajaxParams'] = $ajaxParams;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        $this->rebuildDatagrid();

        return $this;
    }

    /**
     * Get all columns.
     *
     * @return DatagridColumn[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get column dynamically.
     *
     * @param $name
     *
     * @return DatagridColumn
     */
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->getColumn($name);
        }
    }

    /**
     * Set the datagrid helper only on first instantiation.
     *
     * @param DatagridHelper $datagridHelper
     *
     * @return $this
     */
    public function setDatagridHelper(DatagridHelper $datagridHelper)
    {
        $this->datagridHelper = $datagridHelper;

        return $this;
    }

    /**
     * Get datagrid helper.
     *
     * @return DatagridHelper
     */
    public function getDatagridHelper()
    {
        return $this->datagridHelper;
    }

    /**
     * Render the datagrid.
     *
     * @param $options
     * @param $columns
     * @param bool $showStart
     * @param bool $showColumns
     * @param bool $showEnd
     *
     * @return string
     */
    protected function render($options, $columns, $showStart, $showColumns, $showEnd)
    {
        $datagridOptions = $this->datagridHelper->mergeOptions($this->datagridOptions, $options);

        return $this->datagridHelper->getView()
            ->make($datagridOptions['view'])
            ->with(compact('showStart', 'showColumns', 'showEnd'))
            ->with('datagridOptions', $datagridOptions)
            ->with('tableAttrs', $this->datagridHelper->prepareAttributes($this->datagridOptions['attr']))
            ->with('columns', $columns)
            ->with('exclude', $this->exclude)
            ->render();
    }

    /**
     * Get all columns that are not rendered.
     *
     * @return array
     */
    protected function getUnrenderedColumns()
    {
        $unrenderedColumns = [];

        foreach ($this->columns as $column) {
            if (! $column->isRendered()) {
                $unrenderedColumns[] = $column;
                continue;
            }
        }

        return $unrenderedColumns;
    }

    /**
     * Prevent adding columns with same name.
     *
     * @param string $name
     */
    protected function preventDuplicate($name)
    {
        if ($this->has($name)) {
            throw new \InvalidArgumentException('Column ['.$name.'] already exists in the datagrid '.get_class($this));
        }
    }

    /**
     * Check if datagrid is named datagrid.
     */
    protected function checkIfNamedDatagrid()
    {
        if ($this->getDatagridOption('name')) {
            $this->name = array_pull($this->datagridOptions, 'name', $this->name);
        }
    }

    /**
     * Set datagrid builder instance on helper so we can use it later.
     *
     * @param DatagridBuilder $datagridBuilder
     *
     * @return $this
     */
    public function setDatagridBuilder(DatagridBuilder $datagridBuilder)
    {
        $this->datagridBuilder = $datagridBuilder;

        return $this;
    }

    /**
     * @return DatagridBuilder
     */
    public function getDatagridBuilder()
    {
        return $this->datagridBuilder;
    }

    /**
     * Exclude some columns from rendering.
     *
     * @return $this
     */
    public function exclude(array $columns)
    {
        $this->exclude = array_merge($this->exclude, $columns);

        return $this;
    }
}

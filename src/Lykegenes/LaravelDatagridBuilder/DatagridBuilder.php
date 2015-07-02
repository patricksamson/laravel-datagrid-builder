<?php namespace Lykegenes\LaravelDatagridBuilder;

use Illuminate\Contracts\Container\Container;

class DatagridBuilder
{

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @var DatagridHelper
	 */
	protected $datagridHelper;

	/**
	 * @param Container  $container
	 * @param DatagridHelper $datagridHelper
	 */
	public function __construct(Container $container, DatagridHelper $datagridHelper)
	{
		$this->container = $container;
		$this->datagridHelper = $datagridHelper;
	}

	/**
	 * @param       $datagridClass
	 * @param       $options
	 * @param       $data
	 * @return Datagrid
	 */
	public function create($datagridClass, array $options = [], array $data = [])
	{
		$class = $this->getNamespaceFromConfig() . $datagridClass;

		if (!class_exists($class))
		{
			throw new \InvalidArgumentException(
				'Datagrid class with name ' . $class . ' does not exist.'
			);
		}

		$datagrid = $this->container
		                 ->make($class)
		                 ->setDatagridHelper($this->datagridHelper)
		                 ->setDatagridBuilder($this)
		                 ->setDatagridOptions($options)
		                 ->addData($data);

		$datagrid->buildDatagrid();

		return $datagrid;
	}

	/**
	 * Get the namespace from the config
	 *
	 * @return string
	 */
	protected function getNamespaceFromConfig()
	{
		$namespace = $this->datagridHelper->getConfig('default_namespace');

		if (!$namespace)
		{
			return '';
		}

		return $namespace . '\\';
	}

	/**
	 * Get instance of the empty datagrid which can be modified
	 *
	 * @param array $options
	 * @param array $data
	 * @return Form
	 */
	public function plain(array $options = [], array $data = [])
	{
		return $this->container
		            ->make('Lykegenes\LaravelDatagridBuilder\Datagrid')
		            ->setDatagridHelper($this->datagridHelper)
		            ->setDatagridBuilder($this)
		            ->setDatagridOptions($options)
		            ->addData($data);
	}
}
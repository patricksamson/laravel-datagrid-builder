<?php namespace Lykegenes\LaravelDatagridBuilder;

trait DatagridBuilderTrait
{
    /**
     * Create a Datagrid instance
     *
     * @param string $name Full class name of the datagrid class
     * @param array  $options Options to pass to the datagrid
     * @param array  $data additional data to pass to the datagrid
     *
     * @return \Lykegenes\LaravelDatagridBuilder\Datagrid
     */
    protected function datagrid($name, array $options = [], array $data = [])
    {
        return \App::make('datagrid-builder')->create($name, $options, $data);
    }

    /**
     * Create a plain Datagrid instance
     *
     * @param array $options Options to pass to the datagrid
     * @param array $data additional data to pass to the datagrid
     *
     * @return \Lykegenes\LaravelDatagridBuilder\Datagrid
     */
    protected function plain(array $options = [], array $data = [])
    {
        return \App::make('datagrid-builder')->plain($options, $data);
    }
}

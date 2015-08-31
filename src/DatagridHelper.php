<?php namespace Lykegenes\DatagridBuilder;

use Illuminate\Contracts\View\Factory as View;
use Illuminate\Http\Request;

class DatagridHelper
{

    /**
     * @var View
     */
    protected $view;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var DatagridBuilder
     */
    protected $datagridBuilder;

    /**
     * @param View    $view
     * @param Request $request
     * @param array   $config
     */
    public function __construct(View $view, Request $request, array $config = [])
    {
        $this->view    = $view;
        $this->config  = $config;
        $this->request = $request;
    }

    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getConfig($key, $default = null)
    {
        return array_get($this->config, $key, $default);
    }

    /**
     * @return View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Merge options array
     *
     * @param array $first
     * @param array $second
     * @return array
     */
    public function mergeOptions(array $first, array $second)
    {
        return array_replace_recursive($first, $second);
    }

    /**
     * Convert array of attributes to html attributes
     *
     * @param $options
     * @return string
     */
    public function prepareAttributes($options)
    {
        if (!$options) {
            return null;
        }

        $attributes = [];

        foreach ($options as $name => $option) {
            if ($option !== null) {
                $name         = is_numeric($name) ? $option : $name;
                $option       = is_bool($option) ? ($option ? 'true' : 'false') : $option;
                $attributes[] = $name . '="' . $option . '" ';
            }
        }

        return join('', $attributes);
    }

    /**
     * Format the label to the proper format
     *
     * @param string $name
     * @return string
     */
    public function formatLabel($name)
    {
        if (!$name) {
            return null;
        }

        return ucwords(str_replace('_', ' ', $name));
    }
}

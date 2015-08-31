<?php namespace Lykegenes\LaravelDatagridBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ApiResponse
{

    /**
     * The Request instance
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * The Fractal Manager instance
     * @var League\Fractal\Manager
     */
    protected $fractal;

    /**
     * Various request parameters
     * @var String
     */
    protected $page;
    protected $perPage;
    protected $sort;
    protected $order;
    protected $search;
    protected $include;

    /**
     * Construct a new ApiResponse instance
     * @param \Illuminate\Http\Request $request The current request instance
     * @param \League\Fractal\Manager $fractal A Fractal manager instance
     */
    public function __construct(Request $request, Manager $fractal)
    {
        $this->request = $request;
        $this->fractal = $fractal;

        $this->parseRequest();
    }

    /**
     * Parse the request parameters
     */
    protected function parseRequest()
    {
        $this->page    = $this->getRequestParameterValue('page', 1);
        $this->perPage = $this->getRequestParameterValue('per_page', 10);
        $this->sort    = $this->getRequestParameterValue('sort', null);
        $this->order   = $this->getRequestParameterValue('order', 'asc');
        $this->search  = $this->getRequestParameterValue('search', null);
        $this->include = $this->getRequestParameterValue('include', null);
    }

    public function make($stuff, $transformer)
    {
        JsonResponse::create($this->makeArray($stuff, $transformer));
    }

    /**
     * Try to guess what to do with this stuff
     * @param  mixed $stuff       A Class, a Model instance or a Query Builder instance
     * @param  mixed $transformer The Fractal transformer to use
     * @return array              [description]
     */
    public function makeArray($stuff, $transformer)
    {
        if ($stuff instanceof Model) {
            $data = $this->fromModelInstance($stuff, $transformer);
        } elseif ($stuff instanceof Builder) {
            $data = $this->fromQuery($stuff, $transformer);
        } elseif (is_subclass_of($stuff, Model::class)) {
            $data = $this->fromModelClass($stuff, $transformer);
        } elseif (method_exists($stuff, 'toArray')) {
            $data = $stuff->toArray();
        } else {
            $data = array($stuff);
        }

        return $data;
    }

    /**
     * Transform and serialize multiple models from its class
     * @param  mixed $model       An Eloquent Model class
     * @param  mixed $transformer A Fractal Transformer class
     * @return array              The transformed and serialized models collection
     */
    public function fromModelClass($model, $transformer)
    {
        if (is_subclass_of($model, Model::class)) {
            return $this->fromQuery($model::query(), $transformer);
        }
    }

    /**
     * Transform and serialize a single model instance
     * @param  Model $model       An Eloquent Model instance
     * @param  mixed $transformer A Fractal Transformer class
     * @return array              The transformed and serialized model
     */
    public function fromModelInstance($model, $transformer)
    {
        $resource = new Item($model, new $transformer);

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Transform and serialize the results of an Eloquent query
     * @param  Builder $query       An Eloquent Query instance
     * @param  mixed $transformer A Fractal Transformer class
     * @return array              The transformed and serialized query results
     */
    public function fromQuery(Builder $query, $transformer)
    {
        if ($this->search != null && method_exists($query, 'search')) {
            // apply search query
            $query->search($this->search);
        }

        if ($this->sort != null) {
            // apply sorting and ordering
            $query->orderBy($this->sort, $this->order);
        }

        // paginate the results
        $paginator = $query->paginate($this->perPage);

        if ($this->include != null) {
            $this->fractal->parseIncludes($this->include);
        }

        $resource = new Collection($paginator->getCollection(), new $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Get the name of a request parameter from the user's configuration
     * @param  String $key This parameter's key
     * @return String      This parameter's name
     */
    private function getRequestParameterName($key)
    {
        return config('datagrid-builder.api.parameters.' . $key, $key);
    }

    /**
     * Get the value of a request parameter
     * @param  String $key    This parameter's key
     * @param  mixed $default The default value to return
     * @return mixed          This paramter's value
     */
    private function getRequestParameterValue($key, $default = null)
    {
        return $this->request->input($this->getRequestParameterName($key), $default);
    }

}

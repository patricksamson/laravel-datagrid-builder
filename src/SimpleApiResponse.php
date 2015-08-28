<?php namespace Lykegenes\LaravelDatagridBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;

class SimpleApiResponse
{

    /**
     * [$request description]
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * [$fractal description]
     * @var League\Fractal\Manager
     */
    protected $fractal;

    protected $page;
    protected $perPage;
    protected $sort;
    protected $order;
    protected $search;

    public function __construct(Request $request, Manager $fractal)
    {
        $this->request = $request;
        $this->fractal = $fractal;

        $this->parseRequest();
    }

    protected function parseRequest()
    {
        $this->page    = $this->getRequestParameterValue('page', 1);
        $this->perPage = $this->getRequestParameterValue('per_page', 10);
        $this->sort    = $this->getRequestParameterValue('sort', null);
        $this->order   = $this->getRequestParameterValue('order', 'asc');
        $this->search  = $this->getRequestParameterValue('search', null);
    }

    public function fromModel($model, $transformer)
    {
        if (is_subclass_of($model, Model::class))
        {
            return $this->fromQuery($model::query(), $transformer);
        }

    }

    public function fromQuery(Builder $query, $transformer)
    {
        if (method_exists($query, 'search') && $this->search != null)
        {
            // apply search query
            $query->search($this->search);
        }

        if ($this->sort != null)
        {
            // apply sorting and ordering
            $query->orderBy($this->sort, $this->order);
        }

        // paginate the results
        $paginator = $query->paginate($this->perPage);

        $resource = new Collection($paginator->getCollection(), new $transformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $this->fractal->createData($resource)->toJson();
    }

    private function getRequestParameterName($key)
    {
        return config('datagrid-builder.api.parameters.' . $key);
    }

    private function getRequestParameterValue($key, $default = null)
    {
        return $this->request->input($this->getRequestParameterName($key), $default);
    }
}

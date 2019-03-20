<?php
/**
 * Created by PhpStorm.
 * User: wangxinyao
 * Date: 2019/3/15
 * Time: 3:01 PM
 */

namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\GeneralException;

abstract class BaseRepository implements RepositoryContract
{
    /**
     * The repository model
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * The query builder
     *
     * @var \Illuminate\Database\Eloquent\Builder;
     */
    protected $query;

    /**
     * Alias for query limit
     * @var int
     */
    protected $take;

    /**
     * Array of related models
     *
     * @var array
     */
    protected $with = [];

    /**
     * Array of one or more where clause parameters
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * Array of one or more where in clause parameters
     *
     * @var array
     */
    protected $whereIns = [];

    /**
     * Array of scope methods to call on the model
     *
     * @var array
     */
    protected $scopes = [];

    /**
     * Array of one or more Order by column/value pairs
     *
     * @var array
     */
    protected $orderBys = [];

    /**
     * BaseRepository constructor.
     * @throws GeneralException
     */
    public function __construct() {
        $this->makeModel();
    }

    abstract function model();

    /**
     * @throws GeneralException
     */
    public function makeModel() {
        $model = app()->make($this->model());
        if (! $model instanceof Model) {
            throw new GeneralException("Class {$this->model()} must be an instance of ".Model::class);
        }

        $this->model = $model;
    }

    /**
     * Create a new instance of model's query builder
     *
     * @return $this
     */
    public function newQuery() {
        $this->query = $this->model->newQuery();
        return $this;
    }

    public function all(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad();
        $models = $this->query->get($columns);

        $this->unsetClauses();
        return $models;
    }

    public function eagerLoad() {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }

    public function unsetClauses() {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }

    public function setClauses() {
        foreach ($this->wheres as $where) {
            $this->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orderBy) {
            $this->query->orderBy($orderBy['column'], $orderBy['direction']);
        }

        if (isset($this->take) and !is_null($this->take)) {
            $this->query->take($this->take);
        }

        return $this;
    }

    public function setScopes() {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(...$args);
        }

        return $this;
    }

    public function count()
    {
        return $this->get()->count();
    }

    public function create(array $data)
    {
        $this->unsetClauses();
        return $this->model->create($data);
    }

    public function createMultiple(array $data)
    {
        $models = new Collection();
        foreach ($data as $d) {
            $models->push($this->create($d));
        }

        return $models;
    }

    public function delete()
    {
        $this->newQuery()->setClauses()->setScopes();
        $result = $this->query->delete();

        $this->unsetClauses();
        return $result;
    }

    public function deleteById($id) : bool
    {
        $this->unsetClauses();
        return $this->getById($id)->delete();
    }

    public function deleteMultipleById(array $ids) : int
    {
        return $this->model->destroy($ids);
    }

    public function first(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $model = $this->query->firstOrFail($columns);
        $this->unsetClauses();

        return $model;
    }

    public function get(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $models = $this->query->get($columns);
        $this->unsetClauses();

        return $models;
    }

    public function getById($id, array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad();
        return $this->query->findOrFail($id, $columns);
    }

    public function getByColumn($item, $column, array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad();
        return $this->query->where($column, $item)->first($columns);
    }

    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();
        return $models;
    }

    public function updateByColumn($item, $column, array $data, array $options = []) {
        $this->unsetClauses();
        $model = $this->getByColumn($item, $column);
        $model->update($data, $options);

        return $model;
    }

    public function updateById($id, array $data, array $options = [])
    {
        $this->unsetClauses();
        $model = $this->getById($id);
        $model->update($data, $options);

        return $model;
    }

    public function limit($limit)
    {
        $this->take = $limit;
        return $this;
    }

    public function orderBy($column, $direction)
    {
        $this->orderBys[] = compact('column', 'direction');
        return $this;
    }

    public function where($column, $value, $operator = '=')
    {
        $this->wheres[] = compact('column', 'value', 'operator');
        return $this;
    }

    public function whereIn($column, $values)
    {
        $values = is_array($values)? $values : [$values];
        $this->whereIns[] = compact('column', 'values');
        return $this;
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;
        return $this;
    }

    public function __call($scope, $args)
    {
        $this->scopes[$scope] = $args;
        return $this;
    }
}
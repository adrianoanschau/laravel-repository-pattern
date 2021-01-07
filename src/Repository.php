<?php

namespace Anxis\LaravelRepositoryPattern;

use Anxis\LaravelRepositoryPattern\Interfaces\IRepository;
use Anxis\LaravelRepositoryPattern\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Repository implements IRepository
{
    use Filterable;

    /**
     * @var Model
     */
    private $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $id
     * @param array|string[] $columns
     * @param array $relations
     * @return Model
     */
    private function findByCompositeID($id, array $columns = ['*'], array $relations = []): Model
    {
        $ids = explode('-', $id);
        $criteria = collect(array_flip($this->newQuery()->getModel()->primaryKey))->map(function ($value, $key) use ($ids) {
            return $ids[$value];
        })->toArray();
        return $this->findByCriteria($criteria, $columns, $relations);
    }

    /**
     * @param int|string $id
     * @param array|string[] $columns
     * @param array $relations
     * @return Model
     */
    public function findById($id, array $columns = ['*'], array $relations = []): Model
    {
        if (is_string($id) && str_contains($id, '-')) {
            return $this->findByCompositeID($id, $columns, $relations);
        }
        return $this->findByCriteria(['id' => $id], $columns, $relations);
    }

    /**
     * @param array $criteria
     * @param array|string[] $columns
     * @param array $relations
     * @return Model
     */
    public function findByCriteria(array $criteria, array $columns = ['*'], array $relations = []): Model
    {
        return $this->newQuery()->select($columns)->with($relations)->where($criteria)->firstOrFail();
    }

    /**
     * @return mixed
     */
    public function paginate()
    {
        if (!empty($this->filters)) {
            return $this->filterable($this->newQuery()->getModel())->jsonPaginate();
        }
        return $this->newQuery()->jsonPaginate();
    }

    /**
     * @param array $criteria
     * @param array|string[] $columns
     * @param array $relations
     * @return Collection
     */
    public function getByCriteria(array $criteria, array $columns = ['*'], array $relations = []): Collection
    {
        return $this->newQuery()->select($columns)->with($relations)->where($criteria)->get();
    }

    /**
     * @return Builder
     */
    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }
}

<?php

namespace Anxis\LaravelRepositoryPattern;

use Anxis\LaravelRepositoryPattern\Interfaces\IRepository;
use Anxis\LaravelRepositoryPattern\Interfaces\IService;
use Exception;
use Illuminate\Database\Eloquent\Model;

class RepositoryService implements IService
{
    /**
     * @var IRepository
     */
    protected $repository;

    /**
     * BaseService constructor.
     * @param IRepository $repository
     */
    public function __construct(
        IRepository $repository
    )
    {
        $this->repository = $repository;
    }

    /**
     * @param array $attributes
     * @return Model
     * @throws Exception
     */
    public function create(array $attributes): Model
    {
        return $this->repository->newQuery()->create($attributes);
    }

    /**
     * @param string $id
     * @param array $attributes
     * @throws Exception
     */
    public function update(string $id, array $attributes): void
    {
        $model = $this->repository->findById($id);
        $model->update($attributes);
    }

    /**
     * @param string $id
     * @throws Exception
     */
    public function delete(string $id): void
    {
        $model = $this->repository->findById($id);
        $model->delete();
    }
}

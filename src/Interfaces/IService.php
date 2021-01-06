<?php

namespace Anxis\LaravelRepositoryPattern\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface IService
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * @param string $id
     * @param array $attributes
     */
    public function update(string $id, array $attributes): void;

    /**
     * @param string $id
     */
    public function delete(string $id): void;
}

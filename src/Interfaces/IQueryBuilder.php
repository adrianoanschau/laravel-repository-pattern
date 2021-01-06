<?php

namespace Anxis\LaravelRepositoryPattern\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface IQueryBuilder
{
    /**
     * @return Builder
     */
    public function newQuery(): Builder;
}

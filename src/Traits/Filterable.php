<?php

namespace Anxis\LaravelRepositoryPattern\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait Filterable
{
    protected $filters = [];
    protected $mappable = [];

    public function filterable(Model $model)
    {
        $filters = request()->get('filter') ?? [];
        $filters = collect(Arr::only($filters, array_keys($this->filters)));
        $filters->each(function ($value, $filter) use (&$model) {
            $type = $this->filters[$filter];
            $attr = $filter;
            if (isset($this->mappable[$attr])) {
                $attr = $this->mappable[$attr];
            }
            if ($type === 'custom') {
                $method = "get" . Str::studly($filter) . "Filter";
                if (method_exists($this, $method)) {
                    $model = $this->{$method}($model, $attr, $value);
                }
                return;
            }
            $model = $this->where($type, $model, $attr, $value);
        });

        return $model;
    }

    private function where($type, $model, $attr, $value)
    {
        switch($type) {
            case 'number':
                return $model->where($attr, "like", "%$value%");
            case 'string':
                return $model->whereRaw("LOWER($attr) LIKE ('%$value%')");
            default:
                return $model->where($attr, $value);
        }
    }

}

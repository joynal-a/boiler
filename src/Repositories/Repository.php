<?php

namespace Abedin\Boiler\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    /**
     * @abstract
     */
    abstract public static function model();

    /**
     * @return Builder|
     */
    public static function query(): Builder
    {
        return static::model()::query();
    }

    /**
     * @return Builder|Collection|mixed
     */
    public static function getAll(): Collection
    {
        return static::model()::latest()->get();
    }

    /**
     * @return int
     */
    public static function countAll(): int
    {
        return static::model()::getAll()->count();
    }

    /**
     * @return Model|object|null
     */
    public static function first(): Model|null
    {
        return static::query()->first();
    }

    /**
     * @requires int|id
     * @return Model|object|null|null
     */
    public static function find($primaryKey): Model|null
    {
        return static::query()->find($primaryKey);
    }

    /**
     * @requires int|id
     * @return Model|object|404
     */
    public static function findOrFail($primaryKey)
    {
        return static::query()->findOrFail($primaryKey);
    }

    /**
     * @requires int|id
     * @return bool
     */
    public static function delete($primaryKey): bool
    {
        return static::model()::destroy($primaryKey);
    }

    /**
     * @requires Array
     * @return Model|mixed
     */
    public static function create(array $data): Model
    {
        return static::query()->create($data);
    }

    /**
     * @requires Model|Array
     * @return bool
     */
    public static function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }
}

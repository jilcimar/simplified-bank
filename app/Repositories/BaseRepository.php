<?php

namespace App\Repositories;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Contracts\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * BaseRepository constructor.
     */
    public function __construct(
        protected Model|Role $model,
        protected ?string $resourceType = null,
        protected ?Model $resourceInstance = null
    ) {
    }

    /**
     * Get model by ID.
     */
    public function find(string | int $id): Model | JsonResource | null
    {
        return $this->model->find($id);
    }

    /**
     * Get Model by Column.
     */
    public function findBy(string $column, mixed $value): Model|null
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * Get Model by Columns.
     */
    public function findByThrough(array $columns): Model|JsonResource|null
    {
        return $this->model->where($columns)->first();
    }

    /**
     * Handles model before store.
     */
    public function beforeStore(Collection|array $attributes): Model|JsonResource
    {
        return $this->create($attributes, true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Collection|array $attributes, bool $exec = false): Model|JsonResource
    {
        if (!$exec) {
            return $this->beforeStore($attributes);
        }

        return DB::transaction(function () use ($attributes) {
            $attributes = $this->createAttributes($attributes);

            $resource = $this->build($attributes, true);
            $resource->save();

            return $this->afterStore($resource, $attributes);
        });
    }

    /**
     * Handles create action attributes.
     */
    public function createAttributes(Collection|array $attributes): Collection|array
    {
        return $this->filterAttributes($attributes);
    }

    /**
     * Filter attributes
     */
    public function filterAttributes(Collection|array $attributes): Collection|array
    {
        return $attributes;
    }

    /**
     * Build a new object without saving.
     */
    public function build(Collection|array $attributes, bool $force = false): Model|JsonResource
    {
        return $this->fill(
            $this->getInstance(),
            $attributes,
            $force
        );
    }

    /**
     * Get resource instance.
     */
    public function getInstance(): Model|JsonResource
    {
        if (is_null($this->resourceInstance)) {
            $this->resourceInstance = $this->model;
        }

        return $this->resourceInstance;
    }

    /**
     * Handles model after store.
     */
    public function afterStore(Model $resource, Collection|array $attributes): Model|JsonResource
    {
        return $this->afterSave($resource, $attributes);
    }

    /**
     * Handles model after save.
     */
    public function afterSave(Model $resource, Collection|array $attributes): Model|JsonResource
    {
        return $resource;
    }

    /**
     * Return paginated collection
     */
    public function all($queryParams): Collection|JsonResource|LengthAwarePaginator
    {
        return $this->model->paginate(25);
    }

    /**
     * Fills data to the resource.
     */
    public function fill(Model $resource, Collection|array $attributes): Model|JsonResource
    {
        $fillable_columns = Schema::getColumnListing($resource->getTable());
        $attributes = collect($attributes)->only($fillable_columns)->toArray();
        $resource->fill($attributes);

        return $resource;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Model $resource): Model|JsonResource
    {
        return DB::transaction(function () use ($resource) {
            $resource = $this->beforeDelete($resource);

            $resource->delete();

            return $this->afterDelete($resource);
        });
    }

    /**
     * Handles model before delete.
     */
    public function beforeDelete(Model $resource): Model|JsonResource
    {
        return $resource;
    }

    /**
     * Handles model after delete.
     */
    public function afterDelete(Model $resource): Model|JsonResource
    {
        return $resource;
    }

    /**
     * Handles model before update.
     */
    public function beforeUpdate(Model $resource, Collection|array $attributes): Model|JsonResource
    {
        return $this->update($resource, $attributes, true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Model $resource, Collection|array $attributes, bool $exec = false): Model|JsonResource
    {
        if (!$exec) {
            return $this->beforeUpdate($resource, $attributes);
        }

        return DB::transaction(function () use ($resource, $attributes) {
            $attributes = $this->updateAttributes($attributes);

            $resource = $this->fill($resource, $attributes);
            $resource->save();

            return $this->afterUpdate($resource, $attributes);
        });
    }

    /**
     * Handles update action attributes.
     */
    public function updateAttributes(Collection|array $attributes): Collection|array
    {
        return $this->filterAttributes($attributes);
    }

    /**
     * Handles model after update.
     */
    public function afterUpdate(Model $resource, Collection|array $attributes): Model|JsonResource
    {
        return $this->afterSave($resource, $attributes);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasForm;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class CrudController extends Controller
{
    use HasForm;

    public function __construct(
        protected BaseRepository $repository,
        protected Model $model,
        protected array $columns
    ) {
    }

    public function index(): Collection|JsonResource|LengthAwarePaginator
    {
        $queryParams = request()->query();
        return $this->repository->all($queryParams);
    }

    public function store(): Model|JsonResponse|JsonResource
    {
        $params = $this->formParams();

        try {
            return $this->repository->create($params);
        } catch (\Exception $ex) {
            return $this->exceptionMessage($ex);
        }
    }

    public function show(int $id): Model | JsonResource | JsonResponse
    {
        $resource = $this->repository->find($id);

        if (!$resource) {
            abort(404, trans('errors.not_found'));
        }

        return $resource;
    }

    public function update(int $id)
    {
        $resource = $this->repository->find($id);

        if ($resource) {
            $resource = $resource->resource ?? $resource;
            return $this->repository->update($resource, $this->formParams());
        }

        abort(404, trans('errors.not_found'));
    }

    public function destroy(int $id): Model|JsonResource|JsonResponse
    {
        $resource = $this->repository->find($id);

        if ($resource) {
            $resource = $resource->resource ?? $resource;
            return $this->repository->delete($resource);
        }

        abort(404, trans('errors.not_found'));
    }
}

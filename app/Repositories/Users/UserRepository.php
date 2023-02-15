<?php

namespace App\Repositories\Users;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\BaseRepository;
use function bcrypt;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function filterAttributes($attributes): Collection|array
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = bcrypt($attributes['password']);
        }

        return $attributes;
    }

    public function all($queryParams): JsonResource
    {
        $users = QueryBuilder::for(User::class)->allowedFilters(
            AllowedFilter::partial('email', 'email'),
        )->paginate(5);

        return UserResource::collection($users);
    }
}

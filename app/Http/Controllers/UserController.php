<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ExceptionResponse;
use App\Http\Requests\UserFormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use App\Repositories\Users\UserRepository;

class UserController extends CrudController
{
    use ExceptionResponse;

    public function __construct()
    {
        $this->repository = new UserRepository();
        $this->model = new User();
        $this->columns = Schema::getColumnListing($this->model->getTable());

        parent::__construct($this->repository, $this->model, $this->columns);
    }

    public function formRequest(): UserFormRequest
    {
        return app(UserFormRequest::class);
    }
}

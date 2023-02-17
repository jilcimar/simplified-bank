<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ExceptionResponse;
use App\Http\Requests\TransactionFormRequest;
use App\Models\Transaction;
use App\Repositories\Payments\TransactionRepository;
use Illuminate\Support\Facades\Schema;

class TransactionController extends CrudController
{
    use ExceptionResponse;

    public function __construct()
    {
        $this->repository = new TransactionRepository();
        $this->model = new Transaction();
        $this->columns = Schema::getColumnListing($this->model->getTable());

        parent::__construct($this->repository, $this->model, $this->columns);
    }

    public function formRequest(): TransactionFormRequest
    {
        return app(TransactionFormRequest::class);
    }
}

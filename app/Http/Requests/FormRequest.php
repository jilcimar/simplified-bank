<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\ValidatedInput;

class FormRequest extends BaseFormRequest
{
    /**
     * Type of class being validated.
     *
     * @var string
     */
    protected $type = 'App\\Models\\Model';

    /**
     * Form params
     */
    public function params(): ValidatedInput
    {
        return $this->safe();
    }

    /**
     * Define nice names for attributes.
     */
    public function attributes(): array
    {
        return [];
    }
}

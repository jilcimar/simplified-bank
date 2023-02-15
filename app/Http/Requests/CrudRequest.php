<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

abstract class CrudRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return array_merge_recursive(
            $this->baseRules(),
            $this->isMethod('put')
                ? $this->editRules()
                : $this->createRules()
        );
    }

    /**
     * Rules when editing resource.
     */
    protected function editRules(): array
    {
        return [];
    }

    /**
     * Rules when creating resource.
     */
    protected function createRules(): array
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))->errorBag($this->errorBag);
    }

    /**
     * Base rules for both creating and editing the resource.
     */
    abstract protected function baseRules(): array;
}

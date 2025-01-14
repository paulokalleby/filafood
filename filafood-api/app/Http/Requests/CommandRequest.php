<?php

namespace App\Http\Requests;

use App\Enums\CommandsStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'number'                => ['required', 'integer'],
            'products'              => ['required', 'array'],
            'products.*.product_id' => ['required', 'exists:products,id', 'uuid'],
            'products.*.quantity'   => ['required', 'integer', 'min:1'],
        ];

        if ($this->method() == 'PUT') {
            $rules = [];
            $rules['payment_id'] = ['required', 'exists:payments,id', 'uuid'];
            $rules['status']     = ['required', Rule::in(array_keys(CommandsStatusEnum::cases()))];
        }

        return $rules;
    }
}

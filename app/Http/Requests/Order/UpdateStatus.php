<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatuses;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatus extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::enum(OrderStatuses::class)],
        ];
    }
}

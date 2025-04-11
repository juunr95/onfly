<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatuses;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'in:' . implode(',', OrderStatuses::cases())],
            'requester_id' => ['nullable', 'exists:users,id'],
        ];
    }
}

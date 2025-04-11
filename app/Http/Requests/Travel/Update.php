<?php

namespace App\Http\Requests\Travel;

use App\Enums\OrderStatuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'destination' => ['nullable', 'string'],
            'departure_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date'],
            'order.status' => ['nullable', Rule::enum(OrderStatuses::class)],
        ];
    }
}

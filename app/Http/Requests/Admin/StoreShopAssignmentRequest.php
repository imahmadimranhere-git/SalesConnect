<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShopAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_id'      => ['required', 'exists:shops,id'],
            'day_of_week'  => ['required', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'visit_order'  => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'shop_id.required' => 'Please select a shop.',
        ];
    }
}
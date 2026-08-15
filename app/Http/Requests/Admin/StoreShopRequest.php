<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'address'    => ['required', 'string', 'max:255'],
            'area'       => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string', 'max:20'],
            'latitude'   => ['required', 'numeric', 'between:-90,90'],
            'longitude'  => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required'  => 'Please select the shop location on the map.',
            'longitude.required' => 'Please select the shop location on the map.',
        ];
    }
}
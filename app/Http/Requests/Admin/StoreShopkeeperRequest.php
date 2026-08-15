<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopkeeperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_name'    => ['required', 'string', 'max:255'],
            'owner_name'   => ['required', 'string', 'max:255'],
            'address'      => ['required', 'string', 'max:255'],
            'area'         => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:20'],
            'email_prefix' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email_prefix.unique' => 'This email is already taken by another user.',
            'email_prefix.alpha_dash' => 'Email prefix can only contain letters, numbers, and dashes.',
        ];
    }
}
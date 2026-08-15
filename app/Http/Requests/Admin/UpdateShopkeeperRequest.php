<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShopkeeperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('shopkeeper')->id;

        return [
            'shop_name'    => ['required', 'string', 'max:255'],
            'owner_name'   => ['required', 'string', 'max:255'],
            'address'      => ['required', 'string', 'max:255'],
            'area'         => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:20'],
            'email_prefix' => [
                'required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('users', 'email')->ignore($userId),
            ],
        ];
    }
}
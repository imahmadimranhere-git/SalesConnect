<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDistributorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $distributorId = $this->route('distributor')->id;

        return [
            'name'         => ['required', 'string', 'max:255'],
            'email_prefix' => [
                'required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('users', 'email')->ignore($distributorId),
            ],
            'phone'        => ['required', 'string', 'max:20'],
        ];
    }
}
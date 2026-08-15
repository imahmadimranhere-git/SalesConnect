<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->route('admin')->id;

        return [
            'company_name' => ['required', 'string', 'max:255'],
            'owner_name'   => ['required', 'string', 'max:255'],
            'email_prefix' => [
                'required', 'string', 'max:50', 'alpha_dash',
                Rule::unique('users', 'email')->ignore($adminId),
            ],
            'phone'        => ['required', 'string', 'max:20'],
        ];
    }
}
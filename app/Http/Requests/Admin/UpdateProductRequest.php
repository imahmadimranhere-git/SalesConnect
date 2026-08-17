<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'name'           => ['required', 'string', 'max:255'],
            'sku'            => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)],
            'category'       => ['nullable', 'string', 'max:255'],
            'price'          => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'max:2048'],
        ];
    }
}
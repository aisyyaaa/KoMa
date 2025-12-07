<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $base = [
            'seller_id' => ['integer', 'exists:sellers,id'],
            'category_id' => ['integer', 'exists:categories,id'],
            'name' => ['string', 'max:255'],
            'description' => ['string'],
            'price' => ['numeric', 'min:0'],
            'stock' => ['integer', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
            'is_active' => ['boolean'],
        ];

        if ($this->isMethod('post')) {
            $base['seller_id'] = ['required', ...$base['seller_id']];
            $base['category_id'] = ['required', ...$base['category_id']];
            $base['name'] = ['required', ...$base['name']];
            $base['description'] = ['required', ...$base['description']];
            $base['price'] = ['required', ...$base['price']];
            $base['stock'] = ['required', ...$base['stock']];
        } else {
            // For update allow partial
            foreach (['seller_id','category_id','name','description','price','stock'] as $k) {
                $base[$k] = array_merge(['sometimes','required'], (array)$base[$k]);
            }
        }

        return $base;
    }
}

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
            'description' => ['string', 'min:20'],
            'price' => ['numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'sku' => ['string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:255'],
            'condition' => ['in:new,used'],
            'warranty' => ['nullable', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'length' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'primary_images' => ['nullable', 'file', 'image', 'mimes:jpg,png', 'max:5120'],
            'additional_images' => ['nullable', 'array'],
            'additional_images.*' => ['nullable', 'file', 'image', 'mimes:jpg,png', 'max:5120'],
        ];

        if ($this->isMethod('post')) {
            foreach (['category_id','name','description','price','stock','sku','condition'] as $k) {
                $base[$k] = array_merge(['required'], (array)$base[$k]);
            }
            // unique SKU on create
            $base['sku'][] = 'unique:products,sku';
        } else {
            // For update allow partial and ensure SKU unique ignoring current id
            foreach (['seller_id','category_id','name','description','price','stock'] as $k) {
                $base[$k] = array_merge(['sometimes','required'], (array)$base[$k]);
            }
            $productId = $this->route('product');
            $base['sku'][] = 'unique:products,sku,' . $productId;
        }

        return $base;
    }
}

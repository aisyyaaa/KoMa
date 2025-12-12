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
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'], // Tambah lt:price
            'stock' => ['integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'sku' => ['string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:255'],
            'condition' => ['in:new,used'],
            'warranty' => ['nullable', 'integer', 'min:0'],
            
            // Kolom Dimensi/Berat
            'weight' => ['nullable', 'numeric', 'min:0'],
            'length' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],

            // REVISI KRITIS: Tambahkan Aturan Pengiriman
            'shipment_origin_city' => ['string', 'max:100'],
            'base_shipping_cost' => ['nullable', 'numeric', 'min:0'],
            
            // Gambar
            'primary_images' => ['nullable', 'file', 'image', 'mimes:jpg,png', 'max:5120'],
            'additional_images' => ['nullable', 'array'],
            'additional_images.*' => ['nullable', 'file', 'image', 'mimes:jpg,png', 'max:5120'],
        ];

        if ($this->isMethod('post')) {
            // Aturan REQUIRED untuk CREATE
            foreach (['category_id','name','description','price','stock','sku','condition', 'shipment_origin_city'] as $k) {
                $base[$k] = array_merge(['required'], (array)$base[$k]);
            }
            // unique SKU on create
            $base['sku'][] = 'unique:products,sku';
            // Primary image wajib saat create
            $base['primary_images'] = array_merge(['required'], (array)$base['primary_images']);

        } else {
            // Aturan SOMETIMES/REQUIRED untuk UPDATE
            foreach (['seller_id','category_id','name','description','price','stock'] as $k) {
                $base[$k] = array_merge(['sometimes','required'], (array)$base[$k]);
            }
            // Tambahkan shipment_origin_city ke required/sometimes pada update
             $base['shipment_origin_city'] = array_merge(['sometimes', 'required'], (array)$base['shipment_origin_city']);

            // Unique SKU pada update (kecuali untuk produk itu sendiri)
            $productId = $this->route('product');
            $base['sku'][] = 'unique:products,sku,' . $productId;
        }

        return $base;
    }
}
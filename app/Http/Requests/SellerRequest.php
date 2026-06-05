<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SellerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('seller')?->id ?? $this->route('seller') ?? null;

        $base = [
            'store_name' => ['string', 'max:255'],
            'store_description' => ['nullable', 'string'],
            'pic_name' => ['string', 'max:255'],
            'pic_phone' => ['string', 'max:20'],
            'pic_email' => ['email', 'max:255'],
            'pic_street' => ['string', 'max:255'],
            'pic_rt' => ['string', 'max:10'],
            'pic_rw' => ['string', 'max:10'],
            'pic_village' => ['string', 'max:255'],
            'pic_city' => ['string', 'max:255'],
            'pic_province' => ['string', 'max:255'],
            'pic_ktp_number' => ['string', 'max:100'],
            'pic_photo_path' => ['nullable', 'image', 'max:5120'],
            'pic_ktp_file_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'status' => [Rule::in(['PENDING','ACTIVE','REJECTED'])],
            'email_verified_at' => ['nullable', 'date'],
            'password' => ['string', 'min:8', 'confirmed'],
        ];

        if ($this->isMethod('post')) {
            $base['store_name'] = array_merge(['required'], $base['store_name']);
            $base['pic_name'] = array_merge(['required'], $base['pic_name']);
            $base['pic_phone'] = array_merge(['required', Rule::unique('sellers','pic_phone')], $base['pic_phone']);
            $base['pic_email'] = array_merge(['required','email', Rule::unique('sellers','pic_email')], $base['pic_email']);
            $base['pic_street'] = array_merge(['required'], $base['pic_street']);
            $base['pic_rt'] = array_merge(['required'], $base['pic_rt']);
            $base['pic_rw'] = array_merge(['required'], $base['pic_rw']);
            $base['pic_village'] = array_merge(['required'], $base['pic_village']);
            $base['pic_city'] = array_merge(['required'], $base['pic_city']);
            $base['pic_province'] = array_merge(['required'], $base['pic_province']);
            $base['pic_ktp_number'] = array_merge(['required', Rule::unique('sellers','pic_ktp_number')], $base['pic_ktp_number']);
            $base['password'] = array_merge(['required'], $base['password']);
        } else {
            // update: ignore unique checks for current seller
            $base['pic_phone'] = array_merge(['sometimes','required', Rule::unique('sellers','pic_phone')->ignore($id)], $base['pic_phone']);
            $base['pic_email'] = array_merge(['sometimes','required','email', Rule::unique('sellers','pic_email')->ignore($id)], $base['pic_email']);
            $base['pic_ktp_number'] = array_merge(['sometimes','required', Rule::unique('sellers','pic_ktp_number')->ignore($id)], $base['pic_ktp_number']);
            foreach (['store_name','pic_name','pic_street','pic_rt','pic_rw','pic_village','pic_city','pic_province'] as $k) {
                $base[$k] = array_merge(['sometimes','required'], $base[$k]);
            }
            $base['password'] = array_merge(['nullable'], $base['password']);
        }

        return $base;
    }
}

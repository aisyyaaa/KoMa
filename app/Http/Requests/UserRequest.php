<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user')?->id ?? $this->route('user') ?? null;

        $base = [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255'],
            'password' => ['string', 'min:8', 'confirmed'],
            'email_verified_at' => ['nullable', 'date'],
        ];

        if ($this->isMethod('post')) {
            $base['name'] = array_merge(['required'], $base['name']);
            $base['email'] = array_merge(['required', Rule::unique('users','email')], $base['email']);
            $base['password'] = array_merge(['required'], $base['password']);
        } else {
            $base['email'] = array_merge(['sometimes','required', Rule::unique('users','email')->ignore($id)], $base['email']);
            $base['name'] = array_merge(['sometimes','required'], $base['name']);
            $base['password'] = array_merge(['nullable'], $base['password']);
        }

        return $base;
    }
}

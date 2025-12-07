<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $base = [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'visitor_name' => ['required', 'string', 'max:255'],
            'visitor_phone' => ['required', 'string', 'max:50'],
            'visitor_email' => ['required', 'email', 'max:255'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string'],
        ];

        if (! $this->isMethod('post')) {
            // allow partial updates
            foreach (array_keys($base) as $k) {
                $base[$k] = array_merge(['sometimes'], (array)$base[$k]);
            }
        }

        return $base;
    }
}

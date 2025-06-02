<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0', // opcional se já usa variações
            'variations' => 'required|array|min:1',
            'variations.*.name' => 'required|string|max:100',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.stock' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do produto é obrigatório.',
            'variations.required' => 'Pelo menos uma variação é necessária.',
            'variations.*.name.required' => 'O nome da variação é obrigatório.',
            'variations.*.price.required' => 'O preço da variação é obrigatório.',
            'variations.*.stock.required' => 'A quantidade em estoque da variação é obrigatória.',
        ];
    }
}

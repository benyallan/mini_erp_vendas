<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinalizeOrderRequest extends FormRequest
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
            'cep' => 'required|string|size:8',
            'address' => 'required|string|max:255',
            'coupon_code' => 'nullable|string|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cep.required' => 'O CEP é obrigatório.',
            'cep.size' => 'O CEP deve ter exatamente 8 caracteres.',
            'address.required' => 'O endereço é obrigatório.',
            'coupon_code.max' => 'O código do cupom não pode ter mais de 50 caracteres.',
        ];
    }
}

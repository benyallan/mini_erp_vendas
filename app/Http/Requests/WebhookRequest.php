<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class WebhookRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:orders,id'],
            'status' => ['required', 'string', Rule::enum(OrderStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID do pedido é obrigatório.',
            'id.integer' => 'O ID do pedido deve ser um número.',
            'id.exists' => 'O pedido informado não existe.',
            'status.required' => 'O status do pedido é obrigatório.',
            'status.string' => 'O status do pedido deve ser um texto.',
            'status.enum' => 'O status informado não é válido.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'O status informado não é válido.',
            'errors' => $validator->errors(),
        ], 422));
    }
}

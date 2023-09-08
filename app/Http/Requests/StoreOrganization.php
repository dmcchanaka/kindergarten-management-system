<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganization extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'oName' => 'required|string|max:100',
            'oAddress' => 'required|string|max:255',
            'oContact' => 'required|integer|digits_between:10,25',
            'oEmail' => 'required|email|max:100|unique:users,email'
        ];
    }

    public function attributes(): array
    {
        return [
            'oName' => 'Organization name',
            'oAddress' => 'Organization address',
            'oContact' => 'Organization contact number',
            'oEmail' => 'Organization email',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrincipal extends FormRequest
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
            'pName' => 'required|string|max:100',
            'pContact' => 'required|integer|digits_between:10,25',
            'pEmail' => 'required|email|max:100|unique:users,email',
            'pPassword' => 'required|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'pName' => 'Principal name',
            'pContact' => 'Principal contact number',
            'pEmail' => 'Principal email',
            'pPassword' => 'Principal email',
        ];
    }
}

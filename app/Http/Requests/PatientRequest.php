<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'FirstName' => 'required|string|max:255',
            'MiddleName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'MotherName' => 'required|string|max:255',
            'BirthDay' => 'required|date|before:today',
            'NationalNumber' => 'required|string|max:20|unique:patients,NationalNumber',
            'Gender' => 'required|in:male,female',
            'user_id' => 'required|exists:users,id',
        ];
    }
}

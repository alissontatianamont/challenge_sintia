<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FileValidation;

class UploadFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_cp' => ['required', 'file', 'mimes:pdf,csv', 'max:5120', new FileValidation(1, 10, ['nombre','correo','edad'])],

        ];
    }

    public function messages(): array
    {
        return [
            'file_cp.required' => 'El archivo es obligatorio.',
            'file_cp.file' => 'El archivo debe ser un archivo válido.',
            'file_cp.mimes' => 'El archivo debe ser un archivo de tipo: pdf, csv.',
            'file_cp.max' => 'El tamaño máximo del archivo es 5MB.',
        ];
    }
}

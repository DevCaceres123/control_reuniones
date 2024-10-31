<?php

namespace App\Http\Requests\lector;

use App\Http\Requests\BasePrincipalRequest;
use Illuminate\Foundation\Http\FormRequest;

class LectorRequest extends BasePrincipalRequest
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
        $routeName = $this->route()->getName();

        switch ($routeName) {
            case 'lectores.store':
                return [
                    'nombre' => 'required|max:15|unique:lectores,nombre',
                    'descripcion' => 'required|max:100',
                ];
            case 'lectores.update':
                return [
                    'id_usaurio' => 'required|integer',
                    'estado' => 'required|string|in:activo,inactivo',
                    // Más reglas según sea necesario
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
        

         
        ];
    }
}

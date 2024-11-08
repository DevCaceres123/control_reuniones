<?php

namespace App\Http\Requests\Reunion;

use App\Http\Requests\BasePrincipalRequest;
use Illuminate\Foundation\Http\FormRequest;

class ReunionRequest extends BasePrincipalRequest
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
            case 'reuniones.store':
                return [
                    'titulo' => 'required|min:4|max:100',
                    'descripcion' => 'required|max:100',
                    'entrada' => 'required|date_format:H:i',
                    'salida' => 'required|date_format:H:i|after:entrada',
                ];
            case 'reuniones.update':
                return [
                    'id_usaurio' => 'required|integer',
                    'estado' => 'required|string|in:activo,inactivo',
                    // Más reglas según sea necesario
                ];
            case 'reuniones.nueva_asistencia':
                return [
                    // Añade aquí las reglas para 'nueva_asistencia'
                    'role' => 'required|in:entrada,salida',
                    
                ];
            default:
                return [];
        }
    }
}

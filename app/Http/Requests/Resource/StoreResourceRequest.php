<?php

namespace App\Http\Requests\Resource;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
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
            'type' => 'required|string|in:room,equipment,car,house',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название ресурса обязательно для заполнения',
            'name.max' => 'Название ресурса не может превышать 255 символов',
            'type.required' => 'Тип ресурса обязателен для заполнения',
            'type.in' => 'Тип ресурса должен быть одним из: room, equipment, car, house',
            'description.max' => 'Описание не может превышать 1000 символов',
        ];
    }
}

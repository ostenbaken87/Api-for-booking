<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resource_id' => 'required|exists:resources,id',
            'user_id' => 'required|exists:users,id',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|string|in:pending,confirmed,canceled',
        ];
    }

    public function messages(): array
    {
        return [
            'resource_id.required' => 'ID ресурса обязательно для заполнения',
            'user_id.required' => 'ID пользователя обязательно для заполнения',
            'start_time.required' => 'Дата начала бронирования обязательна для заполнения',
            'end_time.required' => 'Дата окончания бронирования обязательна для заполнения',
            'status.required' => 'Статус бронирования обязательно для заполнения',
            'status.in' => 'Статус бронирования должен быть одним из: pending, confirmed, canceled',
        ];
    }
}

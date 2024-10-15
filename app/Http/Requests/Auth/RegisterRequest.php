<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязателено',
            'name.string' => 'Имя должен быть строкой',
            'name.max' => 'Длина имени не должна превышать 255 символов',

            'email.required' => 'email обязателен.',
            'email.string' => 'email должен быть строкой.',
            'email.email' => 'Невалидный емайл',
            'email.max' => 'email не должен превышать 200 символов',
            'email.unique' => 'Уже есть пользователь с таким email',
            'email.lowercase' => 'Пожалуйста, вводите email в нижнем регистре',

            'password.required' => 'Пароль обязателен.',
            'password.string' => 'Пароль должен быть строкой.',
        ];
    }
}

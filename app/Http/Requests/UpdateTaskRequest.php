<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'task_id' => ['required', 'integer'],
            'task_title' => ['required', 'string', 'max:20', 'min:3'],
            'task_text' => ['required', 'string', 'max:200'],
            'tags' => ['required', 'string', 'max:20', 'min:3'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'task_id.required' => 'Id задачи обязателен.',
            'task_id.integer' => 'Id задачи должен быть числом.',

            'task_title.required' => 'Заголовок задачи обязателен.',
            'task_title.string' => 'Заголовок должен быть строкой.',
            'task_title.max' => 'Заголовок не должен превышать 20 символов.',
            'task_title.min' => 'Заголовок должен содержать минимум 3 символа.',

            'task_text.required' => 'Текст задачи обязателен.',
            'task_text.string' => 'Текст должен быть строкой.',
            'task_text.max' => 'Текст задачи не должен превышать 200 символов.',

            'tags.required' => 'Теги обязательны.',
            'tags.string' => 'Теги должны быть строкой.',
            'tags.max' => 'Теги не должны превышать 20 символов.',
            'tags.min' => 'Теги должны содержать минимум 3 символа.',
        ];
    }
}

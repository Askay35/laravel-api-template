<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Укажите имя',
            'name.string' => 'Имя должно быть строкой',
            'name.max' => 'Имя должно быть не более 255 символов',
            'name.min' => 'Имя должно быть не менее 3 символов',
            'email.required' => 'Укажите электронную почту',
            'email.email' => 'Неверный формат электронной почты',
            'email.unique' => 'Электронная почта уже зарегистрирована',
            'password.required' => 'Укажите пароль',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ];
    }
}

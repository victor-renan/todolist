<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateTodoRequest extends TodoFormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['nullable'],
            'description' => ['nullable'],
            'is_done' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'is_done.boolean' => 'O status de finalizado só pode ser verdadeiro ou falso'
        ];
    }
}

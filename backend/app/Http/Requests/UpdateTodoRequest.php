<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateTodoRequest extends TodoFormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                Rule::unique('todos')
                    ->ignore($this->route('id'))
            ],
            'description' => ['nullable'],
            'is_done' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'Já existe uma tarefa com este nome',
            'is_done.boolean' => 'O status de finalizado só pode ser verdadeiro ou falso'
        ];
    }
}

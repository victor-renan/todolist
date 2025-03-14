<?php

namespace App\Http\Requests;

use App\Models\Todo;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;


class TodoFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        $todo = Todo::find($this->id);
        return $todo && $todo->user_id === $this->user()->id;
    }
}
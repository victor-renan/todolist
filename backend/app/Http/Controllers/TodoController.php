<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\TodoFormRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Requests\DeleteTodoRequest;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DB;
use Exception;
use Log;

class TodoController extends Controller
{
    public static int $PaginationCount = 10;

    public function list(Request $request): JsonResponse
    {
        $builder = Todo::where(['user_id' => $request->user()->id]);

        if ($request->title) {
            $builder->where('title', 'like', "%$request->title%");
        }

        if ($request->description) {
            $builder->where('description', 'like', "%$request->description%");
        }

        if ($request->is_done) {
            $builder->where(['is_done' => isset($request->is_done)]);
        }

        return response()->json($builder->paginate(self::$PaginationCount), 200);
    }

    public function details(TodoFormRequest $request): JsonResponse
    {
        return response()->json(Todo::find($request->route('id')), 200);
    }

    public function create(CreateTodoRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $todo = new Todo($validated);

            DB::transaction(function () use ($todo) {
                $todo->save();
            });

            $todo->refresh();

            return response()->json([
                'message' => 'Tarefa criada com sucesso',
                'data' => $todo->toArray(),
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Falha ao criar tarefa, tente novamente mais tarde',
            ], 500);
        }
    }

    public function update(UpdateTodoRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $todo = Todo::find($request->route('id'));

            DB::transaction(function () use ($todo, $validated) {
                $todo->update($validated);
            });

            $todo->refresh();

            return response()->json([
                'message' => 'Tarefa atualizada com sucesso',
                'data' => $todo->toArray(),
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Falha ao atualizar tarefa, tente novamente mais tarde',
            ], 500);
        }
    }

    public function delete(DeleteTodoRequest $request): JsonResponse
    {
        try {
            DB::transaction(function () use ($request) {
                Todo::find($request->route('id'))->delete();
            });

            return response()->json([
                'message' => 'Tarefa deletada com sucesso',
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Falha ao deletar tarefa, tente novamente mais tarde',
            ], 500);
        }
    }

}

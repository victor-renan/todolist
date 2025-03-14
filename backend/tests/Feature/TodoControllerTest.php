<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Todo::factory(30)->create();
    }

    public function test_get_paginated_list(): void
    {
        $structure = [
            'total',
            'per_page',
            'next_page_url',
            'prev_page_url',
            'first_page_url',
            'last_page_url',
            'from',
            'to',
            'data' => [
                '*' => [
                    'title',
                    'description',
                    'is_done',
                    'created_at',
                    'updated_at'
                ]
            ],
        ];

        $response = $this->getJson('/api/todos');
        $response->assertStatus(200);
        $response->assertJsonStructure($structure);

        $response = $this->getJson($response->json()['next_page_url']);
        $response->assertStatus(200);
        $response->assertJsonStructure($structure);
    }

    public function test_get_search(): void
    {
        Todo::query()->delete();

        Todo::factory()->create([
            'title' => 'Foo',
            'description' => 'Lorem',
            'is_done' => false
        ]);

        Todo::factory()->create([
            'title' => 'Bar',
            'description' => 'Lorem',
            'is_done' => false
        ]);

        Todo::factory()->create([
            'title' => 'Baz',
            'description' => 'Ipsum',
            'is_done' => true
        ]);

        $response = $this->getJson('/api/todos?title=Foo');
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->count('data', 1)
                ->etc()
        );

        $response = $this->getJson('/api/todos?description=Lorem');
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->count('data', 2)
                ->etc()
        );

        $response = $this->getJson('/api/todos?is_done=true');
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->count('data', 1)
                ->etc()
        );
    }

    public function test_get_one(): void
    {
        $todo = Todo::latest()->first();

        $response = $this->getJson("/api/todos/$todo->id");

        $response->assertStatus(200);
    }


    public function test_create_todo(): void
    {
        Todo::query()->delete();

        $response = $this->putJson('/api/todos/', [
            'title' => 'My Todo',
            'description' => 'Lorem ipsum dolor sit',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'title',
                'description',
                'is_done',
                'created_at',
                'updated_at',
                'user_id',
            ]
        ]);
    }

    public function test_create_todo_invalid(): void
    {
        Todo::query()->delete();

        $response = $this->putJson('/api/todos/', [
            'title' => 'My Todo',
        ]);

        $response->assertStatus(422);
    }

    public function test_update_todo(): void
    {
        $todo = Todo::latest()->first();

        $data = [
            'is_done' => true,
            'title' => 'Test',
            'description' => 'Tester Driver',
        ];

        $response = $this->patchJson("/api/todos/$todo->id", $data);

        $response->assertStatus(200);
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->where('data.is_done', $data['is_done'])
                ->where('data.title', $data['title'])
                ->where('data.description', $data['description'])
                ->etc()
        );
    }

    public function test_update_todo_invalid(): void
    {
        $todo = Todo::latest()->first();

        $newTodo = Todo::factory()->create([
            'title' => 'NewTodo',
        ]);

        $response = $this->patchJson("/api/todos/$todo->id", [
            'title' => $newTodo->title,
        ]);

        $response->assertStatus(422);
    }

    public function test_delete_todo(): void
    {
        Todo::query()->delete();

        $todo = Todo::factory()->create();

        $response = $this->deleteJson("/api/todos/$todo->id");
        $response->assertStatus(200);

        $this->assertFalse($todo->exists());
    }
}

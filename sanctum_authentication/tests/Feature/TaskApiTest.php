<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        // Create a user and generate a token for authentication
        $user = User::factory()->create();
        $token = $user->createToken('testToken')->plainTextToken;

        return ['Authorization' => 'Bearer ' . $token];
    }

    /** @test */
    public function it_can_list_tasks()
    {
        Task::factory()->count(3)->create();
        $headers = $this->authenticate();
        $response = $this->getJson('/api/tasks', $headers);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'completed', 'created_at', 'updated_at']
                ]
            ]);
    }

    /** @test */
    public function it_can_show_a_task()
    {
        $task = Task::factory()->create();
        $headers = $this->authenticate();
        $response = $this->getJson("/api/tasks/{$task->id}", $headers);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id'    => $task->id,
                    'title' => $task->title,
                ]
            ]);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        $headers = $this->authenticate();
        $data = [
            'title'       => 'New task',
            'description' => 'Task description',
            'completed'   => false,
        ];

        $response = $this->postJson('/api/tasks', $data, $headers);
        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New task']);
        $this->assertDatabaseHas('tasks', ['title' => 'New task']);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create(['title' => 'Old title']);
        $headers = $this->authenticate();
        $data = ['title' => 'Updated title'];

        $response = $this->putJson("/api/tasks/{$task->id}", $data, $headers);
        $response->assertOk()
            ->assertJsonFragment(['title' => 'Updated title']);
        $this->assertDatabaseHas('tasks', ['title' => 'Updated title']);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();
        $headers = $this->authenticate();

        $response = $this->deleteJson("/api/tasks/{$task->id}", [], $headers);
        $response->assertOk()
            ->assertJson(['message' => 'Task deleted successfully']);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}

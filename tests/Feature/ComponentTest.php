<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use App\Models\Component;

class ComponentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_a_component()
    {
        // Create a user and domain
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        // Acting as the user
        $this->actingAs($user);

        // Send a POST request to create a component
        $response = $this->postJson("/cms/builder/{$domain->id}/component", [
            'type' => 'text',
            'properties' => ['content' => '<p>Hello World</p>'],
            'order' => 0
        ]);

        // Assert the response
        $response->assertStatus(200);
        $this->assertDatabaseHas('components', [
            'domain_id' => $domain->id,
            'type' => 'text',
            'order' => 0
        ]);
    }

    /** @test */
    public function user_can_update_a_component()
    {
        // Create a user, domain, and component
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        $component = Component::factory()->create([
            'domain_id' => $domain->id,
            'type' => 'text',
            'properties' => ['content' => '<p>Original content</p>']
        ]);

        // Acting as the user
        $this->actingAs($user);

        // Send a PUT request to update the component
        $response = $this->putJson("/cms/builder/{$domain->id}/component/{$component->id}", [
            'properties' => ['content' => '<p>Updated content</p>'],
            'order' => 1
        ]);

        // Assert the response
        $response->assertStatus(200);
        $this->assertDatabaseHas('components', [
            'id' => $component->id,
            'properties' => json_encode(['content' => '<p>Updated content</p>']),
            'order' => 1
        ]);
    }

    /** @test */
    public function user_can_delete_a_component()
    {
        // Create a user, domain, and component
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);
        $component = Component::factory()->create(['domain_id' => $domain->id]);

        // Acting as the user
        $this->actingAs($user);

        // Send a DELETE request to delete the component
        $response = $this->deleteJson("/cms/builder/{$domain->id}/component/{$component->id}");

        // Assert the response
        $response->assertStatus(200);
        $this->assertDatabaseMissing('components', ['id' => $component->id]);
    }
}
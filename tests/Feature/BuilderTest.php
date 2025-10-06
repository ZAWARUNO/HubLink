<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;
use App\Models\Component;

class BuilderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_access_builder_page()
    {
        // Create a user and domain
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        // Acting as the user
        $this->actingAs($user);

        // Send a GET request to the builder page
        $response = $this->get(route('cms.builder.show', $domain->id));

        // Assert the response
        $response->assertStatus(200);
        $response->assertViewIs('cms.pages.builder');
    }

    /** @test */
    public function user_can_create_a_component()
    {
        // Create a user and domain
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        // Acting as the user
        $this->actingAs($user);

        // Send a POST request to create a component
        $response = $this->postJson(route('cms.builder.component.store', $domain->id), [
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
        // Create a user and domain
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        // Create a component
        $component = Component::factory()->create([
            'domain_id' => $domain->id,
            'type' => 'text',
            'properties' => ['content' => '<p>Original content</p>']
        ]);

        // Acting as the user
        $this->actingAs($user);

        // Send a PUT request to update the component
        $response = $this->putJson(
            route('cms.builder.component.update', [$domain->id, $component->id]),
            [
                'properties' => ['content' => '<p>Updated content</p>'],
                'order' => 1
            ]
        );

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
        // Create a user and domain
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        // Create a component
        $component = Component::factory()->create(['domain_id' => $domain->id]);

        // Acting as the user
        $this->actingAs($user);

        // Send a DELETE request to delete the component
        $response = $this->deleteJson(
            route('cms.builder.component.delete', [$domain->id, $component->id])
        );

        // Assert the response
        $response->assertStatus(200);
        $this->assertDatabaseMissing('components', ['id' => $component->id]);
    }

    /** @test */
    public function user_can_reorder_components()
    {
        // Create a user and domain
        $user = User::factory()->create();
        $domain = Domain::factory()->create(['user_id' => $user->id]);

        // Create components
        $component1 = Component::factory()->create(['domain_id' => $domain->id, 'order' => 0]);
        $component2 = Component::factory()->create(['domain_id' => $domain->id, 'order' => 1]);

        // Acting as the user
        $this->actingAs($user);

        // Send a POST request to reorder components
        $response = $this->postJson(
            route('cms.builder.components.reorder', $domain->id),
            [
                'components' => [
                    ['id' => $component2->id, 'order' => 0],
                    ['id' => $component1->id, 'order' => 1]
                ]
            ]
        );

        // Assert the response
        $response->assertStatus(200);

        // Refresh models
        $component1->refresh();
        $component2->refresh();

        // Assert the order was updated
        $this->assertEquals(1, $component1->order);
        $this->assertEquals(0, $component2->order);
    }
}

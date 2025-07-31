<?php

namespace Tests\Feature\Resource;

use App\Enums\ResourceType;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_get_all_resources(): void
    {
        // Arrange
        Resource::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/resources');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'type',
                        'description',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_create_resource(): void
    {
        // Arrange
        $resourceData = [
            'name' => 'Конференц-зал А',
            'type' => ResourceType::ROOM->value,
            'description' => 'Большой конференц-зал с проектором'
        ];

        // Act
        $response = $this->postJson('/api/v1/resources/resource', $resourceData);

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'resource' => [
                        'id',
                        'name',
                        'type',
                        'description',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ])
            ->assertJson([
                'message' => 'Ресурс успешно создан',
                'data' => [
                    'resource' => [
                        'name' => 'Конференц-зал А',
                        'type' => ResourceType::ROOM->value,
                        'description' => 'Большой конференц-зал с проектором'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('resources', [
            'name' => 'Конференц-зал А',
            'type' => ResourceType::ROOM->value,
            'description' => 'Большой конференц-зал с проектором'
        ]);
    }

    public function test_can_create_resource_without_description(): void
    {
        // Arrange
        $resourceData = [
            'name' => 'Проектор',
            'type' => ResourceType::EQUIPMENT->value
        ];

        // Act
        $response = $this->postJson('/api/v1/resources/resource', $resourceData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'resource' => [
                        'name' => 'Проектор',
                        'type' => ResourceType::EQUIPMENT->value,
                        'description' => null
                    ]
                ]
            ]);

        $this->assertDatabaseHas('resources', [
            'name' => 'Проектор',
            'type' => ResourceType::EQUIPMENT->value,
            'description' => null
        ]);
    }

    public function test_can_get_single_resource(): void
    {
        // Arrange
        $resource = Resource::factory()->create([
            'name' => 'Тестовый ресурс',
            'type' => ResourceType::CAR->value,
            'description' => 'Тестовое описание'
        ]);

        // Act
        $response = $this->getJson("/api/v1/resources/resource/{$resource->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'type',
                    'description',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $resource->id,
                    'name' => 'Тестовый ресурс',
                    'type' => ResourceType::CAR->value,
                    'description' => 'Тестовое описание'
                ]
            ]);
    }

    public function test_can_update_resource(): void
    {
        // Arrange
        $resource = Resource::factory()->create([
            'name' => 'Старое название',
            'type' => ResourceType::ROOM->value,
            'description' => 'Старое описание'
        ]);

        $updateData = [
            'name' => 'Новое название',
            'type' => ResourceType::EQUIPMENT->value,
            'description' => 'Новое описание'
        ];

        // Act
        $response = $this->patchJson("/api/v1/resources/resource/{$resource->id}", $updateData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Ресурс успешно обновлен',
                'data' => [
                    'resource' => [
                        'id' => $resource->id,
                        'name' => 'Новое название',
                        'type' => ResourceType::EQUIPMENT->value,
                        'description' => 'Новое описание'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'name' => 'Новое название',
            'type' => ResourceType::EQUIPMENT->value,
            'description' => 'Новое описание'
        ]);
    }

    public function test_can_update_resource_partially(): void
    {
        // Arrange
        $resource = Resource::factory()->create([
            'name' => 'Исходное название',
            'type' => ResourceType::ROOM->value,
            'description' => 'Исходное описание'
        ]);

        $updateData = [
            'name' => 'Обновленное название'
        ];

        // Act
        $response = $this->patchJson("/api/v1/resources/resource/{$resource->id}", $updateData);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'resource' => [
                        'id' => $resource->id,
                        'name' => 'Обновленное название',
                        'type' => ResourceType::ROOM->value,
                        'description' => null
                    ]
                ]
            ]);

        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'name' => 'Обновленное название',
            'type' => ResourceType::ROOM->value,
            'description' => null
        ]);
    }

    public function test_can_delete_resource(): void
    {
        // Arrange
        $resource = Resource::factory()->create();

        // Act
        $response = $this->deleteJson("/api/v1/resources/resource/{$resource->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Ресурс успешно удален'
            ]);

        $this->assertDatabaseMissing('resources', [
            'id' => $resource->id
        ]);
    }

    public function test_returns_404_for_nonexistent_resource(): void
    {
        // Act
        $response = $this->getJson('/api/v1/resources/resource/999');

        // Assert
        $response->assertStatus(404);
    }

    public function test_returns_404_when_updating_nonexistent_resource(): void
    {
        // Arrange
        $updateData = [
            'name' => 'Новое название'
        ];

        // Act
        $response = $this->patchJson('/api/v1/resources/resource/999', $updateData);

        // Assert
        $response->assertStatus(404);
    }

    public function test_returns_404_when_deleting_nonexistent_resource(): void
    {
        // Act
        $response = $this->deleteJson('/api/v1/resources/resource/999');

        // Assert
        $response->assertStatus(404);
    }

    public function test_validates_required_fields_on_create(): void
    {
        // Act
        $response = $this->postJson('/api/v1/resources/resource', []);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'type']);
    }

    public function test_validates_name_max_length(): void
    {
        // Arrange
        $resourceData = [
            'name' => str_repeat('a', 256), // 256 символов
            'type' => ResourceType::ROOM->value
        ];

        // Act
        $response = $this->postJson('/api/v1/resources/resource', $resourceData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_validates_type_enum_values(): void
    {
        // Arrange
        $resourceData = [
            'name' => 'Тестовый ресурс',
            'type' => 'invalid_type'
        ];

        // Act
        $response = $this->postJson('/api/v1/resources/resource', $resourceData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }

    public function test_validates_description_max_length(): void
    {
        // Arrange
        $resourceData = [
            'name' => 'Тестовый ресурс',
            'type' => ResourceType::ROOM->value,
            'description' => str_repeat('a', 1001) // 1001 символ
        ];

        // Act
        $response = $this->postJson('/api/v1/resources/resource', $resourceData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['description']);
    }

    public function test_validates_update_data(): void
    {
        // Arrange
        $resource = Resource::factory()->create();
        $updateData = [
            'name' => str_repeat('a', 256),
            'type' => 'invalid_type'
        ];

        // Act
        $response = $this->patchJson("/api/v1/resources/resource/{$resource->id}", $updateData);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'type']);
    }
}

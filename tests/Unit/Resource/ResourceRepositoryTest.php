<?php

namespace Tests\Unit\Resource;

use App\Enums\ResourceType;
use App\Models\Resource;
use App\Repositories\Resource\ResourceRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ResourceRepository $resourceRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resourceRepository = new ResourceRepository(new Resource());
    }

    public function test_can_get_all_resources(): void
    {
        // Arrange
        Resource::factory()->count(3)->create();

        // Act
        $result = $this->resourceRepository->all();

        // Assert
        $this->assertCount(3, $result);
        $this->assertInstanceOf(Resource::class, $result->first());
    }

    public function test_can_find_resource_by_id(): void
    {
        // Arrange
        $resource = Resource::factory()->create([
            'name' => 'Тестовый ресурс',
            'type' => ResourceType::ROOM->value
        ]);

        // Act
        $result = $this->resourceRepository->find($resource->id);

        // Assert
        $this->assertInstanceOf(Resource::class, $result);
        $this->assertEquals($resource->id, $result->id);
        $this->assertEquals('Тестовый ресурс', $result->name);
        $this->assertEquals(ResourceType::ROOM, $result->type);
    }

    public function test_throws_exception_when_finding_nonexistent_resource(): void
    {
        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->resourceRepository->find(999);
    }

    public function test_can_create_resource(): void
    {
        // Arrange
        $resourceData = [
            'name' => 'Новый ресурс',
            'type' => ResourceType::EQUIPMENT->value,
            'description' => 'Описание нового ресурса'
        ];

        // Act
        $result = $this->resourceRepository->create($resourceData);

        // Assert
        $this->assertInstanceOf(Resource::class, $result);
        $this->assertEquals('Новый ресурс', $result->name);
        $this->assertEquals(ResourceType::EQUIPMENT, $result->type);
        $this->assertEquals('Описание нового ресурса', $result->description);

        $this->assertDatabaseHas('resources', [
            'name' => 'Новый ресурс',
            'type' => ResourceType::EQUIPMENT->value,
            'description' => 'Описание нового ресурса'
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
            'type' => ResourceType::CAR->value,
            'description' => 'Новое описание'
        ];

        // Act
        $result = $this->resourceRepository->update($resource->id, $updateData);

        // Assert
        $this->assertInstanceOf(Resource::class, $result);
        $this->assertEquals($resource->id, $result->id);
        $this->assertEquals('Новое название', $result->name);
        $this->assertEquals(ResourceType::CAR, $result->type);
        $this->assertEquals('Новое описание', $result->description);

        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'name' => 'Новое название',
            'type' => ResourceType::CAR->value,
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
        $result = $this->resourceRepository->update($resource->id, $updateData);

        // Assert
        $this->assertInstanceOf(Resource::class, $result);
        $this->assertEquals($resource->id, $result->id);
        $this->assertEquals('Обновленное название', $result->name);
        $this->assertEquals(ResourceType::ROOM, $result->type); // Не изменилось
        $this->assertEquals('Исходное описание', $result->description); // Не изменилось

        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'name' => 'Обновленное название',
            'type' => ResourceType::ROOM->value,
            'description' => 'Исходное описание'
        ]);
    }

    public function test_throws_exception_when_updating_nonexistent_resource(): void
    {
        // Arrange
        $updateData = ['name' => 'Новое название'];

        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->resourceRepository->update(999, $updateData);
    }

    public function test_can_delete_resource(): void
    {
        // Arrange
        $resource = Resource::factory()->create();

        // Act
        $this->resourceRepository->delete($resource->id);

        // Assert
        $this->assertDatabaseMissing('resources', [
            'id' => $resource->id
        ]);
    }

    public function test_throws_exception_when_deleting_nonexistent_resource(): void
    {
        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->resourceRepository->delete(999);
    }
}

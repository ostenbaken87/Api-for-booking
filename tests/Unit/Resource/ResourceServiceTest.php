<?php

namespace Tests\Unit\Resource;

use App\Enums\ResourceType;
use App\Models\Resource;
use App\Repositories\Resource\ResourceRepositoryInterface;
use App\Services\ResourceService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class ResourceServiceTest extends TestCase
{
    private ResourceService $resourceService;
    private mixed $resourceRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resourceRepository = \Mockery::mock(ResourceRepositoryInterface::class);
        $this->resourceService = new ResourceService($this->resourceRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_resources(): void
    {
        // Arrange
        $expectedResources = new Collection([
            Resource::factory()->make(['id' => 1]),
            Resource::factory()->make(['id' => 2]),
        ]);

        $this->resourceRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($expectedResources);

        // Act
        $result = $this->resourceService->getAllResource();

        // Assert
        $this->assertSame($expectedResources, $result);
        $this->assertCount(2, $result);
    }

    public function test_get_resource_by_id(): void
    {
        // Arrange
        $expectedResource = Resource::factory()->make(['id' => 1]);

        $this->resourceRepository
            ->shouldReceive('find')
            ->with(1)
            ->once()
            ->andReturn($expectedResource);

        // Act
        $result = $this->resourceService->getResourceById(1);

        // Assert
        $this->assertSame($expectedResource, $result);
    }

    public function test_create_resource(): void
    {
        // Arrange
        $resourceData = [
            'name' => 'Тестовый ресурс',
            'type' => ResourceType::ROOM->value,
            'description' => 'Тестовое описание'
        ];

        $expectedResource = Resource::factory()->make([
            'id' => 1,
            'name' => 'Тестовый ресурс',
            'type' => ResourceType::ROOM->value,
            'description' => 'Тестовое описание'
        ]);

        $this->resourceRepository
            ->shouldReceive('create')
            ->with($resourceData)
            ->once()
            ->andReturn($expectedResource);

        // Act
        $result = $this->resourceService->createResource($resourceData);

        // Assert
        $this->assertSame($expectedResource, $result);
        $this->assertEquals('Тестовый ресурс', $result->name);
        $this->assertEquals(ResourceType::ROOM, $result->type);
    }

    public function test_update_resource(): void
    {
        // Arrange
        $resourceId = 1;
        $updateData = [
            'name' => 'Обновленное название',
            'type' => ResourceType::EQUIPMENT->value
        ];

        $expectedResource = Resource::factory()->make([
            'id' => $resourceId,
            'name' => 'Обновленное название',
            'type' => ResourceType::EQUIPMENT->value
        ]);

        $this->resourceRepository
            ->shouldReceive('update')
            ->with($resourceId, $updateData)
            ->once()
            ->andReturn($expectedResource);

        // Act
        $result = $this->resourceService->updateResource($resourceId, $updateData);

        // Assert
        $this->assertSame($expectedResource, $result);
        $this->assertEquals('Обновленное название', $result->name);
        $this->assertEquals(ResourceType::EQUIPMENT, $result->type);
    }

    public function test_delete_resource(): void
    {
        // Arrange
        $resourceId = 1;

        $this->resourceRepository
            ->shouldReceive('delete')
            ->with($resourceId)
            ->once();

        // Act & Assert
        $this->resourceService->deleteResource($resourceId);
        // Если метод выполнился без исключений, тест пройден
    }
}

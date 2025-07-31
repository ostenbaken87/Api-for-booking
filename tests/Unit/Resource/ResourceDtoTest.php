<?php

namespace Tests\Unit\Resource;

use App\Dto\Resource\ResourceDto;
use App\Enums\ResourceType;
use Tests\TestCase;

class ResourceDtoTest extends TestCase
{
    public function test_can_create_dto(): void
    {
        // Arrange & Act
        $dto = new ResourceDto(
            name: 'Тестовый ресурс',
            type: ResourceType::ROOM,
            description: 'Тестовое описание'
        );

        // Assert
        $this->assertInstanceOf(ResourceDto::class, $dto);
        $this->assertEquals('Тестовый ресурс', $dto->name);
        $this->assertEquals(ResourceType::ROOM, $dto->type);
        $this->assertEquals('Тестовое описание', $dto->description);
    }

    public function test_can_create_dto_with_null_description(): void
    {
        // Arrange & Act
        $dto = new ResourceDto(
            name: 'Тестовый ресурс',
            type: ResourceType::EQUIPMENT,
            description: null
        );

        // Assert
        $this->assertInstanceOf(ResourceDto::class, $dto);
        $this->assertEquals('Тестовый ресурс', $dto->name);
        $this->assertEquals(ResourceType::EQUIPMENT, $dto->type);
        $this->assertNull($dto->description);
    }

    public function test_can_convert_dto_to_array(): void
    {
        // Arrange
        $dto = new ResourceDto(
            name: 'Тестовый ресурс',
            type: ResourceType::HOUSE,
            description: 'Тестовое описание'
        );

        // Act
        $array = $dto->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals([
            'name' => 'Тестовый ресурс',
            'type' => ResourceType::HOUSE->value,
            'description' => 'Тестовое описание'
        ], $array);
    }

    public function test_can_convert_dto_to_array_with_null_description(): void
    {
        // Arrange
        $dto = new ResourceDto(
            name: 'Тестовый ресурс',
            type: ResourceType::EQUIPMENT,
            description: null
        );

        // Act
        $array = $dto->toArray();

        // Assert
        $this->assertIsArray($array);
        $this->assertEquals([
            'name' => 'Тестовый ресурс',
            'type' => ResourceType::EQUIPMENT->value,
            'description' => null
        ], $array);
    }

    public function test_dto_properties_are_readonly(): void
    {
        // Arrange
        $dto = new ResourceDto(
            name: 'Тестовый ресурс',
            type: ResourceType::ROOM,
            description: 'Тестовое описание'
        );

        // Assert
        $this->assertTrue((new \ReflectionProperty($dto, 'name'))->isReadOnly());
        $this->assertTrue((new \ReflectionProperty($dto, 'type'))->isReadOnly());
        $this->assertTrue((new \ReflectionProperty($dto, 'description'))->isReadOnly());
    }
}

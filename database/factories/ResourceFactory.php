<?php

namespace Database\Factories;

use App\Enums\ResourceType;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resource>
 */
class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'type' => fake()->randomElement(ResourceType::cases())->value,
            'description' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the resource is a room.
     */
    public function room(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => ResourceType::ROOM->value,
            'name' => fake()->randomElement([
                'Конференц-зал А',
                'Конференц-зал Б',
                'Переговорная 1',
                'Переговорная 2',
                'Аудитория 101'
            ]),
        ]);
    }

    /**
     * Indicate that the resource is equipment.
     */
    public function equipment(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => ResourceType::EQUIPMENT->value,
            'name' => fake()->randomElement([
                'Проектор',
                'Ноутбук',
                'Принтер',
                'Сканер',
                'Микрофон'
            ]),
        ]);
    }

    /**
     * Indicate that the resource is a car.
     */
    public function car(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => ResourceType::CAR->value,
            'name' => fake()->randomElement([
                'Toyota Camry',
                'Honda Civic',
                'BMW X5',
                'Mercedes C-Class',
                'Audi A4'
            ]),
        ]);
    }

    /**
     * Indicate that the resource is a house.
     */
    public function house(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => ResourceType::HOUSE->value,
            'name' => fake()->randomElement([
                'Загородный дом',
                'Коттедж',
                'Дача',
                'Вилла',
                'Дом отдыха'
            ]),
        ]);
    }
}

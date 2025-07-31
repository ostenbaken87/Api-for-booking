<?php

namespace App\Dto\Resource;

use App\Http\Requests\Resource\StoreResourceRequest;
use App\Http\Requests\Resource\UpdateResourceRequest;
use App\Enums\ResourceType;

class ResourceDto
{
    public function __construct(
        public readonly string $name,
        public readonly ResourceType $type,
        public readonly ?string $description,
    ) {}

    public static function fromStoreRequest(StoreResourceRequest $request): self
    {
        $validated = $request->validated();
        return new self(
            name: $validated['name'],
            type: ResourceType::from($validated['type']),
            description: $validated['description'] ?? null
        );
    }

    public static function fromUpdateRequest(UpdateResourceRequest $request): self
    {
        $validated = $request->validated();
        return new self(
            name: $validated['name'] ?? '',
            type: isset($validated['type']) ? ResourceType::from($validated['type']) : ResourceType::ROOM,
            description: $validated['description'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type->value,
            'description' => $this->description,
        ];
    }
}

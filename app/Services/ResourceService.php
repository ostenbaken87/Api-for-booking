<?php

namespace App\Services;

use App\Models\Resource;
use App\Dto\Resource\ResourceDto;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Resource\ResourceRepositoryInterface;

class ResourceService
{
    public function __construct(private ResourceRepositoryInterface $resourceRepository) {}

    public function getAllResource(): Collection
    {
        return $this->resourceRepository->all();
    }

    public function getResourceById(int $id): Resource
    {
        return $this->resourceRepository->find($id);
    }

    public function createResource(array $data): Resource
    {
        return $this->resourceRepository->create($data);
    }

    public function updateResource(int $id, array $data): Resource
    {
        return $this->resourceRepository->update($id, $data);
    }

    public function deleteResource(int $id): void
    {
        $this->resourceRepository->delete($id);
    }
}

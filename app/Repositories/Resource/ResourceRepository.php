<?php

namespace App\Repositories\Resource;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;

class ResourceRepository implements ResourceRepositoryInterface
{
    public function __construct(private Resource $model){}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): Resource
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Resource
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Resource
    {
        $resource = $this->find($id);
        $resource->update($data);
        return $resource;
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}

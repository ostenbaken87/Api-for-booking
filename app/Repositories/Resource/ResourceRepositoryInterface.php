<?php

namespace App\Repositories\Resource;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;

interface ResourceRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): Resource;
    public function create(array $data): Resource;
    public function update(int $id, array $data): Resource;
    public function delete(int $id): void;
}

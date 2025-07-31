<?php

namespace App\Actions\Resource;

use App\Dto\Resource\ResourceDto;
use App\Http\Requests\Resource\UpdateResourceRequest;
use App\Models\Resource;
use App\Services\ResourceService;

class UpdateResourceAction
{
    public function __construct(
        private ResourceService $resourceService
    ) {}

    public function handle(int $id, UpdateResourceRequest $request): Resource
    {
        $dto = ResourceDto::fromUpdateRequest($request);

        return $this->resourceService->updateResource($id, $dto->toArray());
    }
}

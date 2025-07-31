<?php

namespace App\Actions\Resource;

use App\Dto\Resource\ResourceDto;
use App\Http\Requests\Resource\StoreResourceRequest;
use App\Models\Resource;
use App\Services\ResourceService;

class StoreResourceAction
{
    public function __construct(
        private ResourceService $resourceService
    ) {}

    public function handle(StoreResourceRequest $request): Resource
    {
        $dto = ResourceDto::fromStoreRequest($request);

        return $this->resourceService->createResource($dto->toArray());
    }
}

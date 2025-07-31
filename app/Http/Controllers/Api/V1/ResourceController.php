<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Resource\StoreResourceAction;
use App\Actions\Resource\UpdateResourceAction;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Resource\StoreResourceRequest;
use App\Http\Requests\Resource\UpdateResourceRequest;
use App\Http\Resources\Resource\ResourceResource;
use App\Http\Resources\Resource\ResourceCollection;
use App\Services\ResourceService;
use Illuminate\Http\JsonResponse;

class ResourceController extends BaseController
{
    public function __construct(
        private ResourceService $resourceService,
        private StoreResourceAction $storeResourceAction,
        private UpdateResourceAction $updateResourceAction
    ) {}

    public function index(): ResourceCollection
    {
        $resources = $this->resourceService->getAllResource();
        return new ResourceCollection($resources);
    }

    public function store(StoreResourceRequest $request): JsonResponse
    {
        $resource = $this->storeResourceAction->handle($request);
        return $this->successResponse([
            'resource' => new ResourceResource($resource),
        ],'Ресурс успешно создан');
    }

    public function show(string $id): JsonResponse
    {
        $resource = $this->resourceService->getResourceById((int) $id);
        return response()->json([
            'data' => new ResourceResource($resource)
        ]);
    }

    public function update(UpdateResourceRequest $request, string $id): JsonResponse
    {
        $resource = $this->updateResourceAction->handle((int) $id, $request);
        return $this->successResponse([
            'resource' => new ResourceResource($resource),
        ],'Ресурс успешно обновлен');
    }

    public function destroy(string $id): JsonResponse
    {
        $this->resourceService->deleteResource((int) $id);
        return response()->json([
            'message' => 'Ресурс успешно удален'
        ]);
    }
}

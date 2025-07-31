<?php

namespace App\Repositories\Booking;

use App\Models\Booking;

class BookingRepository implements BookingRepositoryInterface
{
    public function __construct(private Booking $model){}

    public function store(array $data): Booking
    {
        return $this->model->create($data);
    }

    public function isResourceAvailable (int $resourceId, string $startTime, string $endTime): bool
    {
        return !$this->model->where('resource_id', $resourceId)
                            ->where(function($query) use ($startTime, $endTime){
                               $query->whereBetween('start_time', [$startTime, $endTime])
                                     ->orWhereBetween('end_time', [$startTime, $endTime])
                                     ->orWhere(function($q) use ($startTime, $endTime){
                                        $q->where('start_time', '<=', $startTime)
                                          ->where('end_time', '>=', $endTime);
                                     });
                            })
                            ->where('status', '!=', 'canceled')
                            ->exists();
    }

    public function getLatestBookingForResource(int $resourceId): ?Booking
    {
        return $this->model->where('resource_id', $resourceId)
                           ->where('end_time', '>', now())
                           ->where('status', '!=', 'canceled')
                           ->orderBy('end_time', 'desc')
                           ->first();
    }
}

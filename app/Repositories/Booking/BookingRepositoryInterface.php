<?php

namespace App\Repositories\Booking;

use App\Models\Booking;

interface BookingRepositoryInterface
{
    public function store(array $data): Booking;
    public function isResourceAvailable(int $resourceId, string $startTime, string $endTime): bool;
    public function getLatestBookingForResource(int $resourceId): ?Booking;
}

<?php

namespace App\Services;


use App\Models\Booking;
use App\Repositories\Booking\BookingRepositoryInterface;

class BookingService
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository
    ){}

    public function createBooking(array $data):Booking
    {
        return $this->bookingRepository->store($data);
    }

    public function isResourceAvaliable(int $resourceId, string $startTime, string $endTime): bool
    {
        return $this->bookingRepository->isResourceAvailable($resourceId, $startTime, $endTime);
    }

    public function getResourceBusyUntil(int $resourceId): ?string
    {
        $latestBooking = $this->bookingRepository->getLatestBookingForResource($resourceId);
        return $latestBooking?->end_time;
    }
}

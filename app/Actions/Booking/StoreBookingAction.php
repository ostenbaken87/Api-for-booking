<?php

namespace App\Actions\Booking;

use App\Models\Booking;
use App\Dto\Booking\BookingDto;
use App\Services\BookingService;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\Booking\BookingStoreRequest;

class StoreBookingAction
{
    use AsAction;

    public function __construct(
        private BookingService $bookingService
    ){}

    public function handle(BookingStoreRequest $request): Booking
    {
        $dto = BookingDto::fromStoreRequest($request);

        if (!$this->isResourceAvaliable($dto->resource_id, $dto->start_time, $dto->end_time)) {
            $busyUntil = $this->bookingService->getResourceBusyUntil($dto->resource_id);
            throw new \Exception("Ресурс забронирован до $busyUntil");
        }

        return $this->bookingService->createBooking($dto->toArray());
    }

    private function isResourceAvaliable(int $resourceId, string $startTime, string $endTime): bool
    {
        return $this->bookingService->isResourceAvaliable($resourceId, $startTime, $endTime);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController;
use App\Actions\Booking\StoreBookingAction;
use App\Http\Resources\Booking\BookingResource;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Requests\Booking\BookingStoreRequest;

class BookingController extends BaseController
{
    public function  index():BookingCollection
    {
        $booking = Booking::query()->with(['user','resource'])->paginate(5);
        return new BookingCollection($booking);
    }

    /**
     * Создает новое бронирование
     *
     * @param BookingStoreRequest $request
     * @param StoreBookingAction $action
     * @return JsonResponse
     */

    public function store(BookingStoreRequest $request, StoreBookingAction $action): JsonResponse
    {
        try {
            $booking = $action->handle($request);
            return $this->successResponse([
                'booking' => new BookingResource($booking),
            ],'Бронирование успешно создано',201);
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }
}

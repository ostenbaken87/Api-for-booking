<?php

namespace App\Dto\Booking;

use App\Enums\BookingStatus;
use App\Http\Requests\Booking\BookingStoreRequest;

class BookingDto
{
    public function __construct(
        public readonly int $resource_id,
        public readonly int $user_id,
        public readonly string $start_time,
        public readonly string $end_time,
        public readonly BookingStatus $status,
        public readonly ?string $created_at = null,
        public readonly ?string $updated_at = null
    ){}

    public static function fromStoreRequest(BookingStoreRequest $request): self
    {
        $validated = $request->validated();
        return new self(
            resource_id: $validated['resource_id'],
            user_id: $validated['user_id'],
            start_time: $validated['start_time'],
            end_time: $validated['end_time'],
            status: BookingStatus::from($validated['status']),
            created_at: $validated['created_at'],
            updated_at: $validated['updated_at']
        );
    }

    public function toArray(): array
    {
        return [
            'resource_id' => $this->resource_id,
            'user_id' => $this->user_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

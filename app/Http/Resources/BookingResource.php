<?php

namespace App\Http\Resources;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
             'status' => $this->status,

            'user' => new UserResource($this->whenLoaded('user')),
            'event' => new EventResource($this->whenLoaded('event')),


            // 'user_id' => FacadesAuth::user()->id,
            // 'booking_date' => $this->booking_date,
            // 'category' => new CategoryResource(
            //     $this->whenLoaded('event')?->category
            // ),
        ];
    }
}

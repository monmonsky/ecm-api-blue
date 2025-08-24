<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'store' => new StoreResource($this->store),
            'balance' => (float) (string) $this->balance,
            'store_balance_histories' => StoreBalanceHistoryResource::collection($this->whenLoaded('storeBalanceHistories')),
        ];
    }
}

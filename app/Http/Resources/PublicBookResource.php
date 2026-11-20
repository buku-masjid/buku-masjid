<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicBookResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'report_titles' => $this->report_titles,
            'creator_id' => $this->creator_id,
            'report_visibility_code' => $this->report_visibility_code,
            'status_id' => $this->status_id,
            'status' => $this->status_id == 1 ? 'Aktif' : 'Non-Aktif',
            'bank_account_id' => $this->bank_account_id,
            'budget' => $this->budget,
            'report_periode_code' => $this->report_periode_code,
            'start_week_day_code' => $this->start_week_day_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

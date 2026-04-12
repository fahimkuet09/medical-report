<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Report */
class ReportResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cardio_id' => $this->cardio_id,
            'patient_name' => $this->patient_name,
            'age' => $this->age,
            'sex' => $this->sex,
            'patient_id' => $this->patient_id,
            'referred_by' => $this->referred_by,
            'specimen' => $this->specimen,
            'date_received' => $this->date_received?->format('Y-m-d'),
            'date_delivery' => $this->date_delivery?->format('Y-m-d'),
            'ward_cabin' => $this->ward_cabin,
            'bed' => $this->bed,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

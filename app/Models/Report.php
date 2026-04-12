<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'cardio_id',
        'patient_name',
        'age',
        'sex',
        'patient_id',
        'referred_by',
        'specimen',
        'date_received',
        'date_delivery',
        'ward_cabin',
        'bed',
        'remarks',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_received' => 'date',
            'date_delivery' => 'date',
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@bmc-bd.org'],
            [
                'name' => 'BMC Admin',
                'password' => Hash::make('password'),
            ]
        );

        $samples = [
            [
                'cardio_id' => 'ECHO-2026-001',
                'patient_name' => 'Ayesha Rahman',
                'age' => '42',
                'sex' => 'Female',
                'patient_id' => 'BMC-MRN-10482',
                'referred_by' => 'Prof. Dr. Karim — Medicine',
                'specimen' => 'ECHO 2D',
                'date_received' => '2026-04-02',
                'date_delivery' => '2026-04-04',
                'ward_cabin' => 'Cabin 12A',
                'bed' => '—',
                'remarks' => '<p>Routine follow-up. No acute changes noted on review.</p>',
            ],
            [
                'cardio_id' => 'ECHO-2026-002',
                'patient_name' => 'Mohammad Hasan',
                'age' => '58',
                'sex' => 'Male',
                'patient_id' => 'BMC-MRN-22091',
                'referred_by' => 'Dr. Nusrat — Cardiology OPD',
                'specimen' => 'ECHO 2D',
                'date_received' => '2026-04-05',
                'date_delivery' => '2026-04-07',
                'ward_cabin' => 'Ward 4',
                'bed' => 'B-07',
                'remarks' => null,
            ],
            [
                'cardio_id' => 'ECHO-2026-003',
                'patient_name' => 'Fatema Begum',
                'age' => '67',
                'sex' => 'Female',
                'patient_id' => 'BMC-MRN-31804',
                'referred_by' => null,
                'specimen' => 'ECHO 2D',
                'date_received' => '2026-04-08',
                'date_delivery' => '2026-04-09',
                'ward_cabin' => 'ICU',
                'bed' => 'ICU-2',
                'remarks' => '<p><strong>Urgent:</strong> Correlate with troponin and ECG.</p>',
            ],
        ];

        foreach ($samples as $row) {
            Report::query()->updateOrCreate(
                ['cardio_id' => $row['cardio_id']],
                $row
            );
        }
    }
}

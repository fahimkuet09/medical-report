<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class EchoReportSyncService
{
    /**
     * Fetch reports from the configured API and insert only new rows (unique cardio_id).
     *
     * @return array{inserted: int, skipped: int, total_in_response: int}
     */
    public function syncFromRemote(): array
    {
        $url = config('echo_reports.api_url');

        if (empty($url)) {
            throw new \InvalidArgumentException('ECHO_REPORT_API_URL is not configured.');
        }

        try {
            $response = Http::timeout(config('echo_reports.timeout', 30))
                ->acceptJson()
                ->get($url);
        } catch (Throwable $e) {
            Log::error('ECHO report API request failed', ['exception' => $e]);
            throw new \RuntimeException('Could not reach the external report API.', previous: $e);
        }

        if (! $response->successful()) {
            throw new \RuntimeException(
                'External API returned HTTP '.$response->status().'.'
            );
        }

        $payload = $response->json();

        $items = $this->normalizeItems($payload);

        $inserted = 0;
        $skipped = 0;

        foreach ($items as $row) {
            $attributes = $this->mapRow($row);

            if ($attributes === null) {
                continue;
            }

            $cardioId = $attributes['cardio_id'];

            if (Report::query()->where('cardio_id', $cardioId)->exists()) {
                $skipped++;

                continue;
            }

            Report::query()->create($attributes);
            $inserted++;
        }

        return [
            'inserted' => $inserted,
            'skipped' => $skipped,
            'total_in_response' => count($items),
        ];
    }

    /**
     * @param  mixed  $payload
     * @return list<array<string, mixed>>
     */
    protected function normalizeItems(mixed $payload): array
    {
        if (is_array($payload) && $this->isListArray($payload)) {
            return $payload;
        }

        if (is_array($payload)) {
            foreach (['data', 'reports', 'items', 'results'] as $key) {
                $nested = Arr::get($payload, $key);
                if (is_array($nested) && $this->isListArray($nested)) {
                    return $nested;
                }
            }
        }

        return [];
    }

    /**
     * @param  array<int|string, mixed>  $arr
     */
    protected function isListArray(array $arr): bool
    {
        if ($arr === []) {
            return true;
        }

        return array_keys($arr) === range(0, count($arr) - 1);
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>|null
     */
    protected function mapRow(array $row): ?array
    {
        $cardioId = $this->stringOrNull($row, ['cardio_id', 'cardioId', 'id']);

        if ($cardioId === null || $cardioId === '') {
            return null;
        }

        return [
            'cardio_id' => $cardioId,
            'patient_name' => $this->stringOrEmpty($row, ['patient_name', 'patientName', 'name']),
            'age' => $this->stringOrEmpty($row, ['age']),
            'sex' => $this->stringOrEmpty($row, ['sex', 'gender']),
            'patient_id' => $this->stringOrEmpty($row, ['patient_id', 'patientId', 'mrn']),
            'referred_by' => $this->nullableString($row, ['referred_by', 'referredBy', 'referrer']),
            'specimen' => $this->nullableString($row, ['specimen']),
            'date_received' => $this->parseDate($row, ['date_received', 'dateReceived', 'received_at']),
            'date_delivery' => $this->parseDate($row, ['date_delivery', 'dateDelivery', 'delivery_at']),
            'ward_cabin' => $this->nullableString($row, ['ward_cabin', 'wardCabin', 'ward']),
            'bed' => $this->nullableString($row, ['bed']),
            'remarks' => $this->nullableString($row, ['remarks', 'note', 'notes']),
        ];
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $keys
     */
    protected function stringOrNull(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $row)) {
                continue;
            }
            $v = $row[$key];
            if ($v === null) {
                return null;
            }

            return is_string($v) ? $v : (string) $v;
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $keys
     */
    protected function stringOrEmpty(array $row, array $keys): string
    {
        $v = $this->stringOrNull($row, $keys);

        return $v ?? '';
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $keys
     */
    protected function nullableString(array $row, array $keys): ?string
    {
        $v = $this->stringOrNull($row, $keys);
        if ($v === null) {
            return null;
        }

        $t = trim($v);

        return $t === '' ? null : $t;
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $keys
     */
    protected function parseDate(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $row)) {
                continue;
            }
            $v = $row[$key];
            if ($v === null || $v === '') {
                return null;
            }
            if ($v instanceof \DateTimeInterface) {
                return $v->format('Y-m-d');
            }
            $s = is_string($v) ? trim($v) : (string) $v;
            try {
                return \Carbon\Carbon::parse($s)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }
}

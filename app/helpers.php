<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Read a school setting with a sensible fallback.
     * Safe to call before the settings table exists (returns the default).
     */
    function setting(string $key, $default = null)
    {
        try {
            return Setting::get($key, $default);
        } catch (\Throwable $e) {
            return $default;
        }
    }
}

if (!function_exists('grade_for')) {
    /**
     * Resolve a percentage to ['grade' => 'A', 'remark' => 'Excellent']
     * using the school's configured grading scheme.
     */
    function grade_for(float $percentage): array
    {
        $scheme = json_decode((string) setting('grading_scheme', '[]'), true) ?: [];
        // Sort descending by min so the first match wins.
        usort($scheme, fn ($a, $b) => ($b['min'] ?? 0) <=> ($a['min'] ?? 0));
        foreach ($scheme as $band) {
            if ($percentage >= ($band['min'] ?? 0)) {
                return ['grade' => $band['grade'] ?? '-', 'remark' => $band['remark'] ?? ''];
            }
        }
        return ['grade' => '-', 'remark' => ''];
    }
}

if (!function_exists('media_url')) {
    /**
     * Public URL for an uploaded file on the default filesystem disk.
     * Works for the local "public" disk (with storage:link) and for S3.
     */
    function media_url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        try {
            return \Illuminate\Support\Facades\Storage::url($path);
        } catch (\Throwable $e) {
            return asset('storage/' . ltrim($path, '/'));
        }
    }
}

if (!function_exists('money')) {
    /**
     * Format an amount with the school's currency symbol.
     */
    function money($amount): string
    {
        return setting('currency_symbol', '₦') . number_format((float) $amount, 2);
    }
}

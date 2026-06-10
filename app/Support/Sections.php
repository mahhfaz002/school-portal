<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * The three school sections. Classes and subjects each belong to a section so
 * that staff assignment, fee scheduling and admissions can be scoped.
 */
class Sections
{
    public const PRIMARY = 'Primary';
    public const JUNIOR  = 'Junior Secondary';
    public const SENIOR  = 'Senior Secondary';

    public const ALL = [self::PRIMARY, self::JUNIOR, self::SENIOR];

    /** Default class names per section (used for seeding & suggestions). */
    public const DEFAULT_CLASSES = [
        self::PRIMARY => ['Primary 1', 'Primary 2', 'Primary 3', 'Primary 4', 'Primary 5', 'Primary 6'],
        self::JUNIOR  => ['JSS1', 'JSS2', 'JSS3'],
        self::SENIOR  => ['SSS1', 'SSS2', 'SSS3'],
    ];

    /**
     * Best-effort section from a class name (for backfilling legacy classes).
     */
    public static function fromClassName(?string $name): ?string
    {
        if (!$name) {
            return null;
        }
        $n = Str::upper($name);
        if (Str::startsWith($n, 'PRIMARY') || Str::startsWith($n, 'PRY') || Str::startsWith($n, 'BASIC')) {
            return self::PRIMARY;
        }
        if (Str::startsWith($n, 'JSS') || Str::startsWith($n, 'JS')) {
            return self::JUNIOR;
        }
        if (Str::startsWith($n, 'SSS') || Str::startsWith($n, 'SS')) {
            return self::SENIOR;
        }
        return null;
    }
}

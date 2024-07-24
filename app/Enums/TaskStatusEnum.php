<?php

namespace App\Enum;

class TaskStatusEnum
{
    public const PENDING = 'pending';

    public const IN_PROGRESS = 'in_progress';

    public const COMPLETED = 'completed';

    public static function GetEnumsNameByValue(int $id): string
    {
        return match ($id) {
            static::PENDING => 'pending',
            static::IN_PROGRESS => 'in_progress',
            static::COMPLETED => 'completed',
        };
    }

    public static function GetEnumsValueByName(string $name): int
    {
        return match ($name) {
            'pending' => static::PENDING,
            'in_progress' => static::IN_PROGRESS,
            'completed' => static::COMPLETED,
        };
    }

    public static function AllEnumArrayValues(): array
    {
        return [
            static::PENDING,
            static::IN_PROGRESS,
            static::COMPLETED,
        ];
    }
}

<?php

namespace App;

enum PointCategory: string
{
    case COMMITMENT = 'commitment';
    case COLLABORATION = 'collaboration';
    case INITIATIVE = 'initiative';
    case RESPONSIBILITY = 'responsibility';
    case VIOLATION = 'violation';

    public function label(): string
    {
        return match($this) {
            self::COMMITMENT => 'Commitment',
            self::COLLABORATION => 'Collaboration',
            self::INITIATIVE => 'Initiative',
            self::RESPONSIBILITY => 'Responsibility',
            self::VIOLATION => 'Violation',
        };
    }

    public function getPointRange(): array
    {
        return match($this) {
            self::COMMITMENT => ['min' => 1, 'max' => 10],
            self::COLLABORATION => ['min' => 1, 'max' => 10],
            self::INITIATIVE => ['min' => 1, 'max' => 15],
            self::RESPONSIBILITY => ['min' => 1, 'max' => 10],
            self::VIOLATION => ['min' => -10, 'max' => -1],
        };
    }
}

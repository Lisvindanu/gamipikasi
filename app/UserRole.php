<?php

namespace App;

enum UserRole: string
{
    case LEAD = 'lead';
    case CO_LEAD = 'co-lead';
    case BENDAHARA = 'bendahara';
    case SECRETARY = 'secretary';
    case HEAD = 'head';
    case MEMBER = 'member';

    public function label(): string
    {
        return match($this) {
            self::LEAD => 'Lead',
            self::CO_LEAD => 'Co-Lead',
            self::BENDAHARA => 'Bendahara',
            self::SECRETARY => 'Secretary',
            self::HEAD => 'Head of Department',
            self::MEMBER => 'Member',
        };
    }
}

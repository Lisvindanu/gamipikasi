<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationPosition extends Model
{
    protected $fillable = [
        'user_id',
        'position_type',
        'position_name',
        'order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return match($this->position_name) {
            'lead' => 'Lead',
            'co_lead' => 'Co-Lead',
            'bendahara' => 'Bendahara',
            'secretary' => 'Secretary',
            'head_of_event' => 'Head of Event',
            'head_of_public_relation' => 'Head of Public Relation',
            'head_of_media_creative' => 'Head of Media Creative',
            'head_of_human_resource' => 'Head of Human Resource',
            'head_of_machine_learning' => 'Head of Machine Learning',
            'head_of_game_development' => 'Head of Game Development',
            'head_of_iot_development' => 'Head of IoT Development',
            'head_of_web_developer' => 'Head of Web Developer',
            'head_of_curriculum_developer' => 'Head of Curriculum Developer',
            'staff_event' => 'Staff Event',
            'staff_web_developer' => 'Staff Web Developer',
            'staff_hr' => 'Staff HR',
            'staff_media' => 'Staff Media Creative',
            'staff_pr' => 'Staff Public Relation',
            'staff_ml' => 'Staff Machine Learning',
            'staff_iot' => 'Staff IoT',
            'staff_game' => 'Staff Game Development',
            default => $this->position_name,
        };
    }
}

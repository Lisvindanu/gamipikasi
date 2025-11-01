<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_position',
        'organization_order',
        'department_id',
        'total_points',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function points(): HasMany
    {
        return $this->hasMany(Point::class);
    }

    public function givenPoints(): HasMany
    {
        return $this->hasMany(Point::class, 'given_by');
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at');
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function organizationPosition(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrganizationPosition::class);
    }

    public function getOrganizationDisplayNameAttribute(): string
    {
        if (!$this->organization_position) {
            return '';
        }

        return match($this->organization_position) {
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
            default => $this->organization_position,
        };
    }

    // Auto-sync to organization_positions table when organization_position changes
    protected static function booted()
    {
        static::saved(function ($user) {
            if ($user->organization_position) {
                // Update or create in organization_positions table
                OrganizationPosition::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'position_type' => 'core',
                        'position_name' => $user->organization_position,
                        'order' => $user->organization_order ?? 999,
                    ]
                );
            }
        });
    }
}

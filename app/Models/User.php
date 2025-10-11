<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use GalleryJsonMedia\JsonMedia\Contracts\HasMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasMedia
{
    use HasApiTokens, HasFactory, HasRoles, LogsActivity, CausesActivity, HasPanelShield, InteractWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active_status',
        'digital_signature',
        'department_id',
        'position_id'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'digital_signature' => 'array'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name', 'email',
            ])
            ->logOnlyDirty();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->active_status == 1;
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function concerns(): HasMany
    {
        return $this->hasMany(Concern::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, "assigned_to");
    }

    public function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $names = explode(" ", ucwords($this->name));
                $ini = '';
                foreach ($names as $name) {
                    $ini .= $name[0];
                }
                return $ini;
            }
        );
    }

    public function conducives(): HasMany
    {
        return $this->hasMany(Conducive::class);
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function consumptions(): HasMany
    {
        return $this->hasMany(Consumption::class);
    }

    public function infestations(): HasMany
    {
        return $this->hasMany(Infestation::class);
    }

    public function unservicedAreas(): HasMany
    {
        return $this->hasMany(UnservicedArea::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}

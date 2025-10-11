<?php

namespace App\Models;

use App\Traits\HasSchedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;


class
Entom extends Model
{
    use HasFactory, HasRelationships, LogsActivity, HasTags, HasSchedule;

    protected $fillable = [
        'lead_id', 'client_id', 'address_id', 'site_id', 'target_date',
        'client_requests', 'remarks', 'status',
        'requested_by', 'assigned_to', 'conducted_by', 'reviewed_by',
    ];


    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function city(): HasOneThrough
    {
        return $this->hasOneThrough(Address::class, City::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function findings(): HasMany
    {
        return $this->hasMany(Finding::class);
    }

    public function jobOrders(): MorphMany
    {
        return $this->morphMany(JobOrder::class, 'jobable');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }
}

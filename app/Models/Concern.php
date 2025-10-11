<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Concern extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'client_id', 'summary', 'type', 'status', 'assigned_to', 'job_order_id', 'urgency',
        'created_by', 'reported_date', 'address_id', 'site_id', 'concern_type', 'notification_recipients'
    ];

    protected $casts = [
        "notification_recipients" => "array",
    ];
    
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function openedDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->resolutions()->first()->created_at;
            }
        );
    }

    public function resolutions(): HasMany
    {
        return $this->hasMany(Resolution::class);
    }

    public function closedDate(): Attribute|null
    {
        return Attribute::make(
            get: function () {
                return $this->resolutions()->whereClosed(true)->first()?->created_at;
            }
        );
    }

    public function resolutionInterval(): Attribute|null
    {
        return Attribute::make(
            get: function () {
                $closedDate = $this->closedDate;

                if (is_null($closedDate)) {
                    return null;
                }

                $start = Carbon::make($this->openedDate);
                return Carbon::make($closedDate)->shortAbsoluteDiffForHumans($start, true);
            }
        );
    }

    public function lastActivity(): Attribute|null
    {
        return Attribute::make(
            get: function () {
                return $this->resolutions()->orderBy('created_at', 'desc')->first();
            }
        );
    }
}

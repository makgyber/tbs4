<?php

namespace App\Models;

use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use GalleryJsonMedia\JsonMedia\Contracts\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class InspectedArea extends Model implements HasMedia
{
    use HasFactory, InteractWithMedia, LogsActivity;

    protected $fillable = [
        'area', 'reason', 'user_id', 'acknowledged_by',
        'display', 'job_order_id', 'images'
    ];

    protected $casts = ['images' => 'json'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}

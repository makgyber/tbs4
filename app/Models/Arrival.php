<?php

namespace App\Models;

use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use GalleryJsonMedia\JsonMedia\Contracts\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Arrival extends Model implements HasMedia
{
    use HasFactory, LogsActivity;
    use InteractWithMedia;

    protected $fillable = ['job_order_id', 'arrival', 'departure', 'images', 'remarks', 'user_id'];

    protected $casts = [
        'arrival' => 'datetime',
        'departure' => 'datetime',
        'images' => 'array',
    ];

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

}

<?php

namespace App\Models;


use App\Observers\InfestationObserver;
use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use GalleryJsonMedia\JsonMedia\Contracts\HasMedia;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


#[ObservedBy([InfestationObserver::class])]
class Infestation extends Model implements HasMedia
{
    use HasFactory, InteractWithMedia, LogsActivity;

    protected $fillable = [
        'job_order_id', 'pest_type_id', 'reason', 'degree', 'remarks',
        'display', 'area', 'location', 'images', 'user_id'
        , 're_infestation', 'slow_feeding'
    ];

    protected $casts = [
        'images' => 'array',
        'display' => 'boolean',
        're_infestation' => 'boolean',
        'slow_feeding' => 'boolean'
    ];

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

    public function pestType(): BelongsTo
    {
        return $this->belongsTo(PestType::class);
    }

    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

<?php

namespace App\Models;

use App\Observers\TreatmentObserver;
use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use GalleryJsonMedia\JsonMedia\Contracts\HasMedia;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

//use Spatie\MediaLibrary\HasMedia;

//use Spatie\MediaLibrary\InteractsWithMedia;
//use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[ObservedBy(TreatmentObserver::class)]
class Treatment extends Model implements HasMedia
{
    use HasFactory, InteractWithMedia, LogsActivity;

    protected $fillable = [
        'treatment_type', 'location', 'quantity', 'area',
        'display', 'user_id', 'images', 'service_made', 'time_in', 'time_out'
    ];

    protected $casts = [
        'images' => 'array',
        'treatment_type' => 'array'
    ];

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

//    public function registerMediaConversions(Media $media = null): void
//    {
//        $this
//            ->addMediaConversion('preview')
//            ->fit(Manipulations::FIT_CROP, 300, 300)
//            ->nonQueued();
//    }

    protected function getFieldsToDeleteMedia(): array
    {
        return ['images'];
    }
}

<?php

namespace App\Models;

use App\Observers\ConduciveObserver;
use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use GalleryJsonMedia\JsonMedia\Contracts\HasMedia;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


#[ObservedBy([ConduciveObserver::class])]
class Conducive extends Model implements HasMedia
{
    use HasFactory, LogsActivity;
    use InteractWithMedia;

    protected $fillable = [
        'job_order_id', 'area', 'images', 'reason', 'display',
        'location', 'recommendation', 'user_id'
    ];

    protected $casts = ['images' => 'array'];

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected function getFieldsToDeleteMedia(): array
    {
        return ['images'];
    }

}

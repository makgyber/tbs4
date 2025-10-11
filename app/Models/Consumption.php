<?php

namespace App\Models;

use App\Observers\ConsumptionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


#[ObservedBy([ConsumptionObserver::class])]
class Consumption extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'job_order_id',
        'product_id',
        'consumed',
        'turned_over',
        'metric',
        'received_by',
        'user_id'
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class);
    }

    public function doneBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

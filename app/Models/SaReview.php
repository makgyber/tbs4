<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SaReview extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'job_order_id', 'remarks', 'callout_date', 'supervisors', 'technicians',
        'submission_date', 'service_date', 'review_date', 'compliance_date', 'status',
        'review_callout_date', 'input_revision_date'
    ];

    protected $casts = ['remarks' => 'array', 'supervisors' => 'array', 'technicians' => 'array'];

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
}

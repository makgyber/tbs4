<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Proposition extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'job_order_id', 'scope_of_work', 'coverage_area',
        'proximity', 'tech_count', 'equipment', 'layout',
        'tat_per_treatment', 'tat_overall', 'created_by',
        'approved_by', 'approved_at'
    ];

    protected $casts = [
        'scope_of_work' => 'array',
        'layout' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }
}

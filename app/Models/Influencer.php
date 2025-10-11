<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Influencer extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        "entom_survey_id", "age", "gender", "job", "name", "client_id",
        "type", "reason_for_treatment", "purchasing_behavior", "created_by"
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function entomSurvey(): BelongsTo
    {
        return $this->belongsTo(EntomSurvey::class);
    }
}

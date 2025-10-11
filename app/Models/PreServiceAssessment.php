<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreServiceAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_order_id', 'concern_category', 'client_concerns', 'areas_of_concern', 'diy_treatments', 'user_id',
        'area_type', 'area_survey_remarks',

        'reason_for_uninspected_monitoring_stations',
        'inspected_monitoring_stations',
        'uninspected_monitoring_stations',
        'for_replacement_monitoring_stations',


        'gpc_traps_and_baits_inspected_and_replenished',
        'traps_inspected',
        'reason_for_uninspected_traps',

        'reason_for_uninspected_termite',
        'inspected_count_termite',
        'replenished_count_termite',

    ];

    protected $casts = [
        "concern_category" => "array",
        "traps_inspected" => "boolean",
        "area_type" => "array",
        'for_replacement_monitoring_stations' => 'array',
        'gpc_traps_and_baits_inspected_and_replenished' => 'array',

    ];

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

}

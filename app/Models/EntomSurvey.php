<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EntomSurvey extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        "job_order_id",
        "property_type",
        "industry",
        "sub_industry",
        "goods_or_products",
        "subdivision_type",
        "overall_surrounding",
        "barrier_to_treatment",
        "risk_factors",
//        "structure_condition",
//        "wood_composition",
//        "concrete_composition",
//        "floor_area_sqm",
//        "lm",
//        "no_of_floors",
//        "no_of_rooms",
        "special_remarks",
        "survey_remarks",
        "layout_perimeter",
        "structures"
    ];

    protected $casts = [
        "coverage_area" => "array",
        "overall_surrounding" => "array",
        "barrier_to_treatment" => "array",
        "risk_factors" => "array",
        "layout_perimeter" => "array",
        "structures" => "array"
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }


    public function conducives(): HasMany
    {
        return $this->hasMany(Conducive::class);
    }

    public function inspectedAreas(): HasManyThrough
    {
        return $this->hasManyThrough(InspectedArea::class, JobOrder::class);
    }

    public function uninspectedAreas(): HasManyThrough
    {
        return $this->hasManyThrough(UninspectedArea::class, JobOrder::class);
    }


    public function infestations(): HasManyThrough
    {
        return $this->hasManyThrough(Infestation::class, JobOrder::class);
    }

    public function influencers(): HasMany
    {
        return $this->hasMany(Influencer::class);
    }


    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function approvedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }

    public function jobOrder(): BelongsTo
    {
        return $this->BelongsTo(JobOrder::class);
    }

    public function entomSurveyProposals(): HasMany
    {
        return $this->hasMany(EntomSurveyProposal::class);
    }

}

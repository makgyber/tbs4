<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EntomSurveyProposal extends Model
{

    protected $table = "entom_survey_proposals";
    protected $fillable = [
        "label",
        "first_tech_count",
        "first_driver_count",
        "first_treatment_duration",
        "first_transport",
        "first_proximity",
        "followup_tech_count",
        "followup_driver_count",
        "followup_treatment_duration",
        "followup_transport",
        "followup_proximity",
        "duration",
        "scope_of_work",
        "coverage_area",
        "created_by",
        "entom_survey_id",
    ];

    protected $casts = [
        "coverage_area" => "array"
    ];

    public function entomSurvey(): BelongsTo
    {
        return $this->belongsTo(EntomSurvey::class);
    }


    public function otherMaterials(): HasMany
    {
        return $this->hasMany(EntomSurveyProposalMaterial::class);
    }

    public function otherOperationalExpenses(): HasMany
    {
        return $this->hasMany(EntomSurveyProposalOpex::class);
    }

    public function equipment(): HasMany
    {
        return $this->hasMany(EntomSurveyProposalEquipment::class);
    }

    public function entomSurveyProposalProducts(): HasMany
    {
        return $this->hasMany(EntomSurveyProposalProduct::class);
    }
}

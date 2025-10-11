<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EntomSurveyProposalMaterial extends Model
{
    use LogsActivity;

    protected $fillable = ['material', 'amount', 'entom_survey_proposal_id'];

    public function entomSurveyProposal(): BelongsTo
    {
        return $this->belongsTo(EntomSurveyProposal::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}

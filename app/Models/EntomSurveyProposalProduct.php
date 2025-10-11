<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EntomSurveyProposalProduct extends Model
{
    use LogsActivity;

    protected $table = "entom_survey_proposal_products";

    protected $fillable = ['product_id', 'entom_survey_proposal_id', 'allocation', 'metric'];

    public function entomSurveyProposal(): BelongsTo
    {
        return $this->belongsTo(EntomSurveyProposal::class, 'id', 'entom_survey_proposal_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}

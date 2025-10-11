<?php
namespace App\Models;

use App\Models\Proposal;
use App\Models\ScopeTemplate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProposalScopeTemplate extends Pivot
{
    public $incrementing = true;

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function scopeTemplate(): BelongsTo
    {
        return $this->belongsTo(ScopeTemplate::class);
    }
}

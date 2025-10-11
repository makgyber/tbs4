<?php

namespace App\Models;

use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ProposalScopeTemplate;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Proposal extends Model
{
    use HasFactory, LogsActivity, HasComments;

    protected $fillable = [
        'client_id', 'prepared_by', 'proposal_date', 'template', 'approved_by', 'approved_at', 'findings',
        'proposable_id', 'proposable_type', 'sent_at', 'price', 'scope', 'freebies', 'payment_terms', 'salutation',
        'schedule_reference', 'proposed_service', 'delivery_type', 'status', 'original_price', 'recommendations'
    ];

    protected $casts = [
        'scope' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function proposable(): MorphTo
    {
        return $this->morphTo(name: __FUNCTION__, type: "proposable_type", id: "proposable_id");
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by', 'id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, "proposable_id", 'id');
    }

    public function scopeTemplates(): BelongsToMany
    {
        return $this->belongsToMany(ScopeTemplate::class);
    }

    public function proposalScopeTemplates(): HasMany
    {
        return $this->hasMany(ProposalScopeTemplate::class);
    }


}

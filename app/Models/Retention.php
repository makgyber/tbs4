<?php

namespace App\Models;

use App\Enums\RetentionLeadStatus;
use App\Traits\HasComments;
use App\Traits\HasStateTransitions;
use App\Traits\HasStatusTimeline;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Retention extends Model implements Sortable
{
    use HasFactory, SortableTrait, HasComments, LogsActivity, HasStatusTimeline;

    protected $fillable = [
        'summary', 'client_id', 'terminating_contract_id', 'renewal_contract_id', 'assigned_to', 'order_column',
        'status', 'won_at'
    ];

    protected $casts = [
        'status' => RetentionLeadStatus::class,
        "won_at" => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function terminatingContract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'terminating_contract_id');
    }

    public function renewalContract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'renewal_contract_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function proposals(): MorphMany
    {
        return $this->morphMany(Proposal::class, 'proposable');
    }

}

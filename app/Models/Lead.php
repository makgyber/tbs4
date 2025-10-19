<?php

namespace App\Models;

use App\Enums\AcquisitionLeadStatus;
use App\Observers\LeadObserver;
use App\Traits\HasComments;
use App\Traits\HasStatusTimeline;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

#[ObservedBy([LeadObserver::class])]
class Lead extends Model implements Sortable
{
    use HasFactory, HasTags, HasRelationships, SortableTrait, LogsActivity, HasComments, HasStatusTimeline;

    protected $fillable = [
        'concerns', 'status', 'client_id', 'assigned_to', 'is_reacquisition',
        'lead_type', 'source', 'received_on', 'service_type', 'code', 'won_at'
    ];

    protected $casts = [
        'received_on' => 'date',
        'won_at' => 'date',
        'status' => AcquisitionLeadStatus::class,
        'is_reacquisition' => 'bool'
    ];

    public function sites(): HasManyDeep
    {
        return $this->hasManyDeep(Site::class, [Client::class, Address::class], ['id', 'client_id'])
            ->withIntermediate(Address::class)
            ->withIntermediate(Client::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function proposal(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->proposals()?->latest()->first();
            }
        );
    }

    public function proposals(): MorphMany
    {
        return $this->morphMany(Proposal::class, 'proposable');
    }

    public function entom(): HasOne
    {

        return $this->hasOne(Entom::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function jobOrders(): MorphMany
    {
        return $this->morphMany(JobOrder::class, 'jobable');
    }

    public function contractType(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->contract?->contract_type ?? '';
            }
        );
    }

    public function clientClass(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->client?->classification ?? '';
            }
        );
    }

    public function propertyType(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->contract?->property_type ?? '';
            }
        );
    }

    public function districts(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->client?->addresses->filter(fn($it) => in_array($it->province_id,
                    [66, 67, 68, 69]))->map(fn($it
                ) => $it->province->name)->unique()->all() ?? [];
            }
        );
    }

    public function addresses(): HasManyThrough
    {
        return $this->hasManyThrough(Address::class, Client::class);
    }
}

<?php

namespace App\Models;

use App\Enums\PropertyType;
use App\Traits\HasComments;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Client extends Model
{
    use HasFactory, LogsActivity, HasRelationships, CausesActivity, HasComments, HasTags;

    protected $fillable = [
        'name', 'contact_information', 'classification', 'client_since', 'tin', 'tax_code', 'billing_name',
        'billing_address', 'xeroid'
    ];

    protected $casts = [
        'contact_information' => 'json',
        'client_since' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }

    public function sites(): HasManyThrough
    {
        return $this->hasManyThrough(Site::class, Address::class);
    }

    public function jobOrders(): HasManyThrough
    {
        return $this->hasManyThrough(JobOrder::class, Address::class);
    }

    public function entom(): HasMany
    {
        return $this->hasMany(Entom::class);
    }

    public function fullAddress()
    {
        $fullAddress = '';
        foreach ($this->addresses as $address) {
            $fullAddress .= $address->street.' '.$address->barangay?->name.' '.$address->city->name.'<br/>';
        }
        return Str::of($fullAddress)->toHtmlString();
    }

    public function concerns(): HasMany
    {
        return $this->hasMany(Concern::class);
    }

    public function latestConcern(): HasOne
    {
        return $this->hasOne(Concern::class)->latestOfMany();
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function demographics(): HasMany
    {
        return $this->hasMany(Demographic::class);
    }

    public function psychographics(): HasMany
    {
        return $this->hasMany(Psychographic::class);
    }

    public function propertyType(): Attribute
    {
        return Attribute::make(
            get: function () {
                switch ($this->addresses->count()) {
                    case 0:
                        return PropertyType::None->name;
                    case 1:
                        return PropertyType::Single->name;
                    default:
                        return PropertyType::Multiple->name;
                }
            }
        );

    }

    public function serviceableAreas(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->sites->pluck('areas')
                    ->map(fn($t) => collect($t)->pluck('area'))
                    ->flatten()
                    ->unique()
                    ->filter(fn($t) => !is_null($t))
                    ->all();
            }
        );

    }

    public function contactNumbers(): Attribute
    {
        return Attribute::make(
            get: function () {
                return collect($this->contact_information)->pluck('value', 'type')->all();
            }
        );

    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function currentCriticality(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->contracts
                    ->filter(fn($t) => $t->status === 'closed')
                    ->map(fn($t) => $t->criticality)
                    ->flatten()
                    ->unique()
                    ->first();
            }
        );

    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}

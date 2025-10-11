<?php

namespace App\Models;

use App\Observers\ContractObserver;
use App\Traits\HasActivityStatusInterval;
use App\Traits\HasComments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Str;

#[ObservedBy(ContractObserver::class)]
class Contract extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia, HasComments, HasActivityStatusInterval;

    protected $fillable = [
        'client_id', 'contract_type', 'code', 'engagement', 'scope_of_work',
        'visits', 'frequency', 'start', 'end', 'assigned_to', 'status', 'duration_type',
        'contract_price', 'payment_status', 'payment_terms', 'engagement_type', 'lead_id', 'criticality',
        'supervisor_id', 'supervisor_reviewed', 'supervisor_reviewed_at', 'sites', 'is_direct',
        'tin', 'tax_code', 'billing_name', 'billing_address', 'property_type', 'won_at'
    ];

    protected $casts = [
        'supervisor_reviewed' => 'bool',
        'supervisor_reviewed_at' => 'datetime',
        'sites' => 'array',
        'is_direct' => 'bool',
        'won_at' => 'date',
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

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, "assigned_to", "id");
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }

    public function entom(): HasOne
    {
        return $this->hasOne(Entom::class);
    }

    public function jobOrders(): MorphMany
    {
        return $this->morphMany(JobOrder::class, 'jobable');
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class)->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('allotment', "unit")
            ->withTimestamps();
    }


    public function latestJobOrder(): MorphOne
    {
        return $this->morphOne(JobOrder::class, 'jobable')
            ->ofMany(['target_date' => 'max', 'id' => 'max'], function (Builder $query) {
                $query->whereDate('target_date', '<=', now());
            });
    }

    public function nextJobOrder(): MorphOne
    {
        return $this->morphOne(JobOrder::class, 'jobable')
            ->ofMany(['target_date' => 'max', 'id' => 'max'], function (Builder $query) {
                $query->whereDate('target_date', '>=', now());
            });
    }

    public function contractExtensions(): HasMany
    {
        return $this->hasMany(ContractExtension::class);
    }

    public function latestContractExtension(): HasOne
    {
        return $this->hasOne(ContractExtension::class)->ofMany(['extended_to' => 'max', 'id' => 'max']);
    }

    public function retentionLead(): HasOne
    {
        return $this->hasOne(Retention::class, 'terminating_contract_id', 'id');
    }

    public function renewalLead(): HasOne
    {
        return $this->hasOne(Retention::class, 'renewal_contract_id', 'id');
    }

    public function contractLocations(): Attribute
    {
        return Attribute::make(
            get: function () {
                $locations = [];
                foreach ($this->contractAreas as $area) {
                    if (!is_null($area->locations)) {
                        $locations = array_merge($locations, $area->locations);
                    }
                }
                return array_unique($locations);
            }
        );
    }

    public function contractAreas(): HasMany
    {
        return $this->hasMany(ContractArea::class);
    }

    public function sites(): HasManyThrough
    {
        return $this->hasManyThrough(Site::class, Address::class);
    }

    public function demographics(): HasMany
    {
        return $this->hasMany(Demographic::class, "client_id", "client_id");
    }

    public function psychographics(): HasMany
    {
        return $this->hasMany(Psychographic::class, "client_id", "client_id");
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class, "client_id", "client_id");
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'id');
    }

    public function extractedPropertyType(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->property_type) {
                    return $this->property_type;
                }

                foreach ($this->addresses as $address) {
                    foreach ($address->sites as $site) {
                        if ($site->label != null) {
                            $this->property_type = $site->type;
                            $this->save();
                            return $site->type;
                        }
                    }
                }

                return "";
            }
        );
    }

    public function completedJobOrders(): Attribute
    {
        return Attribute::make(get: function () {
            return $this->jobOrders->where('status', 'completed')
                ->map(function ($it) {
                    return Carbon::make($it->target_date)->format('Y-m-d').' - '.Str::ucwords($it->job_order_type);
                })->toArray();
        });
    }

    public function consumedProducts(): Attribute
    {
        return Attribute::make(
            get: function () {
                $consumptions = $this->jobOrders->where('status', 'completed')
                    ->whereNotIn('job_order_type', [
                        "deliver billing", "pick-up check", "deliver letter/report", "meeting",
                        "DAY OFF/WALANG PASOK",
                        "RELEASE/BODEGERO/OFFICE", "DRIVE/HATID", "OFFICE REPAIR (VEHICLE/EQUIPMENT)",
                        "Pick up File",
                        "TRAINING/SEMINAR", "OFFICE WORK", "DELIVER ONLY", "DELIVER RENEWAL LETTER",
                        "DELIVER CONTRACT",
                        "Absent"
                    ])
                    ->map(fn($it) => $it->consumptions->map(fn($co
                    ) => [$co->product->name.'|'.$co->metric => $co->consumed])->all())->filter(fn($re
                    ) => $re)->flatten(1)->all();

                if (empty($consumptions)) {
                    return "";
                }
                $final = [];

                foreach ($consumptions as $consumption) {
                    foreach ($consumption as $product => $consumed) {
                        if (array_key_exists($product, $final)) {
                            $final[$product] += $consumed;
                        } else {
                            $final[$product] = $consumed;
                        }
                    }
                }

                return collect($final)->map(function ($consumed, $product) {
                    $product_metric = explode("|", $product);
                    return $product_metric[0].' = '.$consumed.''.$product_metric[1];
                })->flatten(1)->all();
            }
        );
    }
}

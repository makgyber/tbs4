<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Site extends Model
{
    use HasFactory, LogsActivity;

    protected $casts = [
        'areas' => 'array'
    ];

    protected $fillable = ['label', 'contact_person', 'contact_number', 'type', 'serviceable_area', 'areas'];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function areaList(): array
    {
        return collect($this->areas)
            ->filter(fn($it) => $it['area'] != null)
            ->pluck('area', 'area')
            ->all();
    }

    public function jobOrders(): HasMany
    {
        return $this->hasMany(JobOrder::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'unit', 'description', 'available', 'low_threshold', 'code', 'product_category_id'];

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function jobOrders(): BelongsToMany
    {
        return $this->belongsToMany(JobOrder::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function contracts(): BelongsToMany
    {
        return $this->belongsToMany(Contract::class)
            ->withPivot(['alloted', 'consumed'])
            ->withTimestamps();
    }

    public function consumption(): HasMany
    {
        return $this->hasMany(Consumption::class);
    }

    public function entomSurveyProposals(): BelongsToMany
    {
        return $this->belongsToMany(EntomSurveyProposal::class)
            ->withTimestamps()
            ->withPivot(['allocation', 'metric']);
    }
}

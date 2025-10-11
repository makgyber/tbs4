<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractArea extends Model
{
    use HasFactory;

    protected $fillable = ['contract_id', 'area', 'locations', 'site_id'];
    protected $casts = ['locations' => 'array'];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}

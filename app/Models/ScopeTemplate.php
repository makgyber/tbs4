<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ScopeTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['label', 'summary', 'show'];

    protected $casts = ['show' => 'boolean'];

    public function proposals(): BelongsToMany
    {
        return $this->belongsToMany(Proposal::class)->withTimestamps();
    }
}

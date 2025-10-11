<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'is_important', 'is_positive', 'commented_by', 'criticality'];

    protected $casts = [
        'is_important' => 'boolean',
        'is_positive' => 'boolean',
    ];

    /**
     * Get the parent commentable model (post or video).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'commentable_type', 'commentable_id');
    }

    public function commentedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commented_by', 'id');
    }

    public function clients(): MorphToMany
    {
        return $this->morphedByMany(Client::class, 'commentable');
    }

    public function leads(): MorphToMany
    {
        return $this->morphedByMany(Lead::class, 'commentable');
    }

    public function contracts(): MorphToMany
    {
        return $this->morphedByMany(Contract::class, 'commentable');
    }

    public function jobOrders(): MorphToMany
    {
        return $this->morphedByMany(JobOrder::class, 'commentable');
    }
}

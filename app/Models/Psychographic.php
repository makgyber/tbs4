<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Psychographic extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'profile_type', 'client_values',
        'personality_traits', 'communication_channel', 'client_purchase_behavior',
        'service_style', 'client_type', 'reason_to_purchase'
    ];
    protected $casts = ['personality_traits' => 'array', 'service_style' => 'array'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Demographic extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'name', 'profile_type',
        'age', 'gender', 'company_name', 'education',
        'years_in_topbest', 'industry', 'sec',
        'position', 'tenure', 'birthdate'
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

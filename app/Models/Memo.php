<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Memo extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['receiver', 'sender', 'details', 'type'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver', 'id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender', 'id');
    }
}

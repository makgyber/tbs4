<?php

namespace App\Models;

use Carbon\Carbon;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model implements Eventable
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'ends_at',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }


    public function toCalendarEvent(): array|CalendarEvent
    {
        // TODO: Implement toCalendarEvent() method.
        return CalendarEvent::make($this)
            ->title($this->title)
            ->start(Carbon::make($this->starts_at)->addHours(8))
            ->end(Carbon::make($this->ends_at)->addHours(8))
//            ->allDay(true)
            ->action('edit')
            ->backgroundColor('#088')
            ->resourceIds(['tasks']);
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }
}

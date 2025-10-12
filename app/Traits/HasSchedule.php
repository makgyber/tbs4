<?php

namespace App\Traits;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasSchedule
{
    public function schedules(): MorphToMany
    {
        return $this->morphToMany(Schedule::class,  'schedulable');
    }
}

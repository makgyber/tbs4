<?php

namespace App\Observers;

use App\Models\JobOrder;
use Carbon\Carbon;

class JobOrderObserver
{
    /**
     * Handle the JobOrder "created" event.
     */
    public function created(JobOrder $jobOrder): void
    {
        //
    }

    /**
     * Handle the JobOrder "updated" event.
     */
    public function updated(JobOrder $jobOrder): void
    {
        if ($jobOrder->wasChanged(['target_date'])) {
            $original = Carbon::make($jobOrder->getOriginal('target_date'));

            if (!$original->isSameDay(Carbon::make($jobOrder->target_date))) {
                $jobOrder->teams()->detach();

                if ($jobOrder->status === 'scheduled') {
                    $jobOrder->status = 'unscheduled';
                    $jobOrder->save();
                }
            }
        }
    }

    /**
     * Handle the JobOrder "deleted" event.
     */
    public function deleted(JobOrder $jobOrder): void
    {
        //
    }

    /**
     * Handle the JobOrder "restored" event.
     */
    public function restored(JobOrder $jobOrder): void
    {
        //
    }

    /**
     * Handle the JobOrder "force deleted" event.
     */
    public function forceDeleted(JobOrder $jobOrder): void
    {
        //
    }
}

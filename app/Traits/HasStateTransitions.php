<?php

namespace App\Traits;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

trait HasStateTransitions
{
    public function stateTransitions(Model $record) : array {
        $statusUpdates = collect($record->statusTimeline)->filter(function ($value, $key) {
            return $key!='';
        })->toArray();

        $currentStatus = $record->status->value;

        if ($currentStatus == 'declined' or (count($statusUpdates) == 1 and $currentStatus == 'won')) {
            $day = (int) ceil(Carbon::now('Asia/Manila')->diffInDays($record->created_at, true));
            return ["$currentStatus after $day days "];
        }

        if (count($statusUpdates) == 1) {
            $day = (int) ceil(Carbon::now('Asia/Manila')->diffInDays($statusUpdates[$currentStatus], true));
            return ["at $currentStatus for $day days"];
        }

        $timelines = [];
        $lastStatus = '';
        $lastDate = '';
        foreach($statusUpdates as $status => $date) {
            /* at start */
            if ($lastStatus == '') {
                $lastStatus = $status;
                $lastDate = $date;
                $day = (int) ceil(Carbon::now('Asia/Manila')->diffInDays($date, true));
                $timelines[] .= "at $status for $day days";
                continue;
            }
            if ($lastStatus == $currentStatus) {
                break;
            }

            if ($status == 'won') {
                $wonDays = (int) ceil(Carbon::now('Asia/Manila')->diffInDays($record->created_at, true));
                $timelines[] .= "$status after $wonDays days";
                break;
            }

            $days = (int) ceil(Carbon::make($date)->diffInDays($lastDate, true));
            $timelines[] .= "at $status for $days days";

            $lastStatus = $status;
            $lastDate = $date;

        }
        return $timelines;
    }
}

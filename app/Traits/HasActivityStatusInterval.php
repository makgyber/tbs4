<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasActivityStatusInterval
{
    public $startStatus = 'pending';
    public $endStatus = 'closed';

    public function intervalDays(): Attribute
    {
        return Attribute::make(
            get: function () {
                $started = null;
                $completed = null;
                foreach ($this->activities()->getResults() as $activity) {

                    foreach ($activity->properties as $item) {
                        if (isset($item['status']) && $item['status'] == $this->startStatus) {
                            $started = Carbon::make($item['updated_at']);
                        }
                        if (isset($item['status']) && $item['status'] == $this->endStatus) {
                            $completed = Carbon::make($item['updated_at']);
                        }
                    }
                }

                print_r($this->startStatus . ' ' . $started->toDateTimeString());
                print_r($this->endStatus . ' ' . $completed->toDateTimeString());

                if (is_null($started)) {
                    print("no pending found");
                    return 0;
                }


                if (is_null($completed)) {
                    print("ongoing");
                    return Carbon::now()->diffForHumans($started);
                }

                return $completed->diffForHumans($started);
            }
        );
    }
}

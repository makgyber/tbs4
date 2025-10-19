<?php

namespace App\Traits;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasStatusTimeline
{
    public function statusTimeline(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->activities
                    ->pluck('updated_at', 'properties.attributes.status')
                    ->toArray();
            }
        );
    }
}

<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasComments
{
    public function comments(): MorphToMany
    {
        return $this->morphToMany(Comment::class,  'commentable');
    }
}

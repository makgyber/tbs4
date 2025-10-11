<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostServiceAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_order_id', 'explanation_checklist', 'post_treatment_checklist', 'client_remarks', 'representative',
        'next_visit_notes', 'additional_concerns', 'user_id'
    ];

    protected $casts = [
        'explanation_checklist' => 'array',
        'post_treatment_checklist' => 'array',
    ];

}

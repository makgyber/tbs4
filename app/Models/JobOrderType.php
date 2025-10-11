<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderType extends Model
{
    use HasFactory;
    protected $fillable = ["name", 'code', 'instruction_list', 'contract_type_list'];

    protected $casts = [
        "instruction_list" => "array",
        'contract_type_list' => 'array'
    ];
}

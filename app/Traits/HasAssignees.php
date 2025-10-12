<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasAssignees
{
    protected function getAssignees(): array {
        $query = "select distinct(users.name) from leads";
        $query .= " join users on leads.assigned_to = users.id";
        $query .= " where year(leads.created_at) = ?";
        $query .= " order by users.name ";
        return  DB::select($query, [now()->year]);
    }
}

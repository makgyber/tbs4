<?php

namespace App\Observers;

use App\Models\Contract;
use App\Notifications\ContractFlagged;
use Illuminate\Support\Facades\Notification;

class ContractObserver
{
    /**
     * Handle the Contract "created" event.
     */
    public function created(Contract $contract): void
    {
        //
    }

    /**
     * Handle the Contract "updated" event.
     */
    public function updated(Contract $contract): void
    {
        if ($contract->wasChanged(['criticality']) && $contract->criticality === "high") {
            Notification::route('mail', 'carlo.lazaro@topbest.ph')
                ->notify(new ContractFlagged($contract));
        }

    }

    /**
     * Handle the Contract "deleted" event.
     */
    public function deleted(Contract $contract): void
    {
        //
    }

    /**
     * Handle the Contract "restored" event.
     */
    public function restored(Contract $contract): void
    {
        //
    }

    /**
     * Handle the Contract "force deleted" event.
     */
    public function forceDeleted(Contract $contract): void
    {
        //
    }
}

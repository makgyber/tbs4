<?php

namespace App\Observers;

use App\Filament\Resources\EnhancedJobOrderResource;
use App\Models\Treatment;
use App\Models\User;
use Filament\Notifications\Notification;

class TreatmentObserver
{
    /**
     * Handle the Treatment "created" event.
     */
    public function created(Treatment $treatment): void
    {
        if ($treatment->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Treatment has been created for '.$treatment->jobOrder->client->name)
                ->body(function () use ($treatment) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $treatment->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the Treatment "updated" event.
     */
    public function updated(Treatment $treatment): void
    {
        if ($treatment->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Treatment has been updated for '.$treatment->jobOrder->client->name)
                ->body(function () use ($treatment) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $treatment->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the Treatment "deleted" event.
     */
    public function deleted(Treatment $treatment): void
    {
        //
    }

    /**
     * Handle the Treatment "restored" event.
     */
    public function restored(Treatment $treatment): void
    {
        //
    }

    /**
     * Handle the Treatment "force deleted" event.
     */
    public function forceDeleted(Treatment $treatment): void
    {
        //
    }
}

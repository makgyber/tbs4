<?php

namespace App\Observers;

use App\Filament\Resources\EnhancedJobOrderResource;
use App\Models\Infestation;
use App\Models\User;
use Filament\Notifications\Notification;

class InfestationObserver
{
    /**
     * Handle the Infestation "created" event.
     */
    public function created(Infestation $infestation): void
    {
        if ($infestation->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Infestation has been created for '.$infestation->jobOrder->client->name)
                ->body(function () use ($infestation) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $infestation->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the Infestation "updated" event.
     */
    public function updated(Infestation $infestation): void
    {
        if ($infestation->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Infestation has been updated for '.$infestation->jobOrder->client->name)
                ->body(function () use ($infestation) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $infestation->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the Infestation "deleted" event.
     */
    public function deleted(Infestation $infestation): void
    {
        //
    }

    /**
     * Handle the Infestation "restored" event.
     */
    public function restored(Infestation $infestation): void
    {
        //
    }

    /**
     * Handle the Infestation "force deleted" event.
     */
    public function forceDeleted(Infestation $infestation): void
    {
        //
    }
}

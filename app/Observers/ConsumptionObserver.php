<?php

namespace App\Observers;

use App\Filament\Resources\EnhancedJobOrderResource;
use App\Models\Consumption;
use App\Models\User;
use Filament\Notifications\Notification;

class ConsumptionObserver
{
    /**
     * Handle the Consumption "created" event.
     */
    public function created(Consumption $consumption): void
    {
        if ($consumption->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Consumption has been created for '.$consumption->jobOrder->client->name)
                ->body(function () use ($consumption) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $consumption->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the Consumption "updated" event.
     */
    public function updated(Consumption $consumption): void
    {
        if ($consumption->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Consumption has been updated for '.$consumption->jobOrder->client->name)
                ->body(function () use ($consumption) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $consumption->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the Consumption "deleted" event.
     */
    public function deleted(Consumption $consumption): void
    {
        //
    }

    /**
     * Handle the Consumption "restored" event.
     */
    public function restored(Consumption $consumption): void
    {
        //
    }

    /**
     * Handle the Consumption "force deleted" event.
     */
    public function forceDeleted(Consumption $consumption): void
    {
        //
    }
}

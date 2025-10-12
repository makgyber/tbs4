<?php

namespace App\Observers;

use App\Filament\Resources\EnhancedJobOrderResource;
use App\Models\UnservicedArea;
use App\Models\User;
use Filament\Notifications\Notification;

class UnservicedAreaObserver
{
    /**
     * Handle the UnservicedArea "created" event.
     */
    public function created(UnservicedArea $unservicedArea): void
    {
        if ($unservicedArea->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Unserviced areas has been created for '.$unservicedArea->jobOrder->client->name)
                ->body(function () use ($unservicedArea) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $unservicedArea->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the UnservicedArea "updated" event.
     */
    public function updated(UnservicedArea $unservicedArea): void
    {
        if ($unservicedArea->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Unserviced areas has been updated for '.$unservicedArea->jobOrder->client->name)
                ->body(function () use ($unservicedArea) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $unservicedArea->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the UnservicedArea "deleted" event.
     */
    public function deleted(UnservicedArea $unservicedArea): void
    {
        //
    }

    /**
     * Handle the UnservicedArea "restored" event.
     */
    public function restored(UnservicedArea $unservicedArea): void
    {
        //
    }

    /**
     * Handle the UnservicedArea "force deleted" event.
     */
    public function forceDeleted(UnservicedArea $unservicedArea): void
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Filament\Resources\EnhancedJobOrderResource;
use App\Models\Conducive;
use App\Models\User;
use Filament\Notifications\Notification;


class ConduciveObserver
{
    /**
     * Handle the Conducive "created" event.
     */
    public function created(Conducive $conducive): void
    {
        if ($conducive->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Conducive has been created for '.$conducive->jobOrder->client->name)
                ->body(function () use ($conducive) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $conducive->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the Conducive "updated" event.
     */
    public function updated(Conducive $conducive): void
    {
        if ($conducive->jobOrder->saReview) {
            $recipient = User::find(99);
            Notification::make()
                ->title('Conducive has been updated for '.$conducive->jobOrder->client->name)
                ->body(function () use ($conducive) {
                    $url = EnhancedJobOrderResource::getUrl('view',
                        ['record' => $conducive->jobOrder->id, 'activeRelationManager' => 0]);
                    return str(
                        "<a href='$url'>Click to view.</a>"
                    )
                        ->sanitizeHtml();
                })
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Handle the Conducive "deleted" event.
     */
    public function deleted(Conducive $conducive): void
    {
        //
    }

    /**
     * Handle the Conducive "restored" event.
     */
    public function restored(Conducive $conducive): void
    {
        //
    }

    /**
     * Handle the Conducive "force deleted" event.
     */
    public function forceDeleted(Conducive $conducive): void
    {
        //
    }
}

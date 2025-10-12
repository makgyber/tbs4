<?php

namespace App\Notifications;

use App\Filament\Resources\Contracts\ContractResource;
use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractFlagged extends Notification
{
    use Queueable;

    private Contract $contract;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Contract #'.$this->contract->code.' flagged as CRITICAL.')
            ->line('Customer name: '.$this->contract->client->name)
            ->action('View contract details', url(ContractResource::getUrl('view', ['record' => $this->contract->id])))
            ->line('This email was triggered by a change in contract criticality level.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }
}

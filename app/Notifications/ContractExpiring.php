<?php

namespace App\Notifications;

use App\Filament\Resources\Contracts\ContractResource;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiring extends Notification
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
        $url = url(ContractResource::getUrl('edit', ['record' => $this->contract->id]));
        return (new MailMessage)
            ->bcc(['carlo.lazaro@topbest.ph', 'jammerkiai@gmail.com'])
            ->line('Contract for '.$this->contract->client->name.' is expiring on '.Carbon::make($this->contract->end)->toFormattedDateString())
            ->action('Click here to access contract details', $url)
            ->line('Do not reply to this email.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

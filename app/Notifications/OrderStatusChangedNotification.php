<?php

namespace App\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public TravelOrder $travelOrder)
    {}

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('O status do seu pedido de viagem foi atualizado.')
            ->line('Novo status: ' . $this->travelOrder->status)
            ->action('Ver pedido', url('/travel-orders/' . $this->travelOrder->id))
            ->line('Obrigado por usar nosso serviço!');
    }
}

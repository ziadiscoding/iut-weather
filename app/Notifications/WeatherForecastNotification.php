<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeatherForecastNotification extends Notification
{
    use Queueable;

    private $csvPath;

    public function __construct($csvPath)
    {
        $this->csvPath = $csvPath;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject('Weather Forecast')
                ->line('Here is your weather forecast.')
                ->attach(storage_path('app/' . $this->csvPath));
    }
}
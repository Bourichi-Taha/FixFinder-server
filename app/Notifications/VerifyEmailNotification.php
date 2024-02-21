<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
  use Queueable;


  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $verificationUrl = $this->verificationUrl($notifiable);

    // Customize the verification URL for API routes
    $verificationUrl = str_replace(url('/'), env('APP_URL'), $verificationUrl);

    return (new MailMessage)
      ->subject('Verify your email address')
      ->line('Please click the button below to verify your email address.')
      ->action('Verify Email Address', $verificationUrl)
      ->line('If you did not create an account, no further action is required.');
  }
  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      //
    ];
  }
}

<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        VerifyEmail::toMailUsing(function ($notifiable) {
            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->greeting("Hi {$notifiable->first_name}")
                ->subject(__('Verify your email address to complete your registration.'))
                ->line(__('To complete your registration, please click the button below to verify your email address.'))
                ->action(__('Verify Email Address'), $verifyUrl)
                ->line(__('If you did not create an account, no further action is required.'));
        });
    }
}

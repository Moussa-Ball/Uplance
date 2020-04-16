<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;

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

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = URL::temporarySignedRoute(
                'password.reset',
                Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'token' => $token,
                    'email' => $notifiable->email,
                ]
            );

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->greeting("Hi {$notifiable->first_name}")
                ->subject(__('Forgotten Password Reset'))
                ->line(__('You are receiving this email because we received a password reset request for your account.'))
                ->action(__('Reset Password'), $resetUrl)
                ->line(__('This password reset link will expire in 60 minutes.'))
                ->line(__('If you did not request a password reset, no further action is required.'));
        });
    }
}

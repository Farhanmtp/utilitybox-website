<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(255);

        $this->checkAndCreateStorageSymlink();
        $this->createConfigVars();
        $this->setConfigVars();

        if (request()->get('dpl') == 1) {
            $reset = request()->get('rst') == 1;
            Artisan::call('app:deploy', ['--force' => true, '--reset' => $reset]);
        }
    }

    /**
     * Check the local storage symbolic link and Create it if does not exist.
     */
    private function checkAndCreateStorageSymlink()
    {
        $symlink = public_path('storage');
        $storage_path = storage_path('app/public');

        try {
            /*if (file_exists($symlink) && is_dir($symlink) && !is_link($symlink)) {
                File::deleteDirectory($symlink);
            }*/

            if (!is_link($symlink)) {
                $this->app->make('files')->link($storage_path, $symlink);
            }
        } catch (\Exception $e) {
            alert_message($e->getMessage());
        }
    }

    private function createConfigVars()
    {
        try {
            // Get all settings from the database
            $settings = Cache::remember('settings', (60 * 60 * 24), function () {
                $settings = Setting::where('status', 1)->get();
                return $settings;
            });

            // Bind all settings to the Laravel config, so you can call them like
            if ($settings->count() > 0) {
                foreach ($settings as $setting) {
                    if (!empty($setting->values)) {
                        foreach ($setting->values as $subKey => $value) {
                            if (!empty($value)) {

                                if (in_array($subKey, ['cache_expiration'])) {
                                    $value = (int)$value;
                                    if ($value) {
                                        $value = $value * 60;  // convert cache time from minutes to seconds
                                    } else {
                                        $value = (60 * 60 * 24);
                                    }
                                }
                                Config::set('settings.' . $setting->key . '.' . $subKey, $value);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Config::set('settings.error', true);
            Config::set('settings.app.logo', asset('images/logo.png'));
        }
    }

    /**
     * Update the config vars
     */
    private function setConfigVars()
    {
        // App
        $app_name = config('settings.app.app_name');
        if ($app_name) {
            Config::set('app.name', $app_name);
        }

        // Mail

        $mail_driver = config('settings.mail.driver');
        Config::set('mail.default', env('MAIL_MAILER', $mail_driver));


        if ($from_email = config('settings.mail.from_email')) {
            Config::set('mail.from.address', $from_email);
        }

        if ($app_name) {
            Config::set('mail.from.name', $app_name);
        }

        // Sendmail
        if ($sendmail_path = config('settings.mail.sendmail_path')) {
            Config::set('mail.mailers.sendmail.path', $sendmail_path);
        }

        //SMTP
        if ($smtp_host = config('settings.mail.host')) {
            Config::set('mail.mailers.smtp.host', $smtp_host);
        }
        if ($smtp_port = config('settings.mail.port')) {
            Config::set('mail.mailers.smtp.port', $smtp_port);
        }
        if ($smtp_encryption = config('settings.mail.encryption')) {
            Config::set('mail.mailers.smtp.encryption', ($smtp_encryption == 'null' ? null : $smtp_encryption));
        }
        if ($smtp_username = config('settings.mail.username')) {
            Config::set('mail.mailers.smtp.username', $smtp_username);
        }
        if ($smtp_password = config('settings.mail.password')) {
            Config::set('mail.mailers.smtp.password', $smtp_password);
        }
        if ($smtp_auth_mode = config('settings.mail.auth_mode')) {
            Config::set('mail.mailers.smtp.auth_mode', ((empty($smtp_auth_mode) || $smtp_auth_mode == 'null') ? null : $smtp_auth_mode));
        }
        if ($smtp_timeout = config('settings.mail.timeout')) {
            Config::set('mail.mailers.smtp.timeout', $smtp_timeout);
        }

        // Mailgun
        if ($mailgun_domain = config('settings.mail.mailgun_domain')) {
            Config::set('services.mailgun.domain', $mailgun_domain);
        }
        if ($mailgun_secret = config('settings.mail.mailgun_secret')) {
            Config::set('services.mailgun.secret', $mailgun_secret);
        }

        // Amazon SES
        if ($ses_key = config('settings.mail.ses_key')) {
            Config::set('services.ses.key', $ses_key);
        }
        if ($ses_secret = config('settings.mail.ses_secret')) {
            Config::set('services.ses.secret', $ses_secret);
        }
        if ($ses_region = config('settings.mail.ses_region')) {
            Config::set('services.ses.region', $ses_region);
        }
    }
}

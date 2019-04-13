<?php

namespace Vicens\LaravelSms;

use Vicens\LaravelSms\Channels\SmsChannel;
use Vicens\LaravelSms\Contracts\Channels\SmsChannel as SmsChannelInterface;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class LaravelSmsServiceProvider extends LaravelServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/config.php' => config_path('sms.php')
        ], 'config');

        // 注册通知渠道
        Notification::extend('sms', function () {
            return $this->app->make(SmsChannelInterface::class);
        });
    }

    /**
     * 注册服务
     */
    public function register()
    {
        $this->app->singleton(Manager::class, function () {
            return new Manager($this->app);
        });

        $this->app->alias(Manager::class, 'sms.manager');

        $this->app->bind(SmsChannelInterface::class, SmsChannel::class);
    }

    /**
     * 服务提供
     *
     * @return array
     */
    public function provides()
    {
        return ['sms.manager'];
    }
}

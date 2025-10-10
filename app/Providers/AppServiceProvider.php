<?php

namespace App\Providers;

use App\Models\OrderItem;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // =================================================================
        // **修正: 强制在生产环境中使用 HTTPS**
        // 解决了在反向代理后，Laravel 错误地将内部端口（如 :80）
        // 附加到 HTTPS 链接上的问题。
        // =================================================================
        URL::forceScheme('https');

        // 注册OrderItem观察者
        OrderItem::observe(OrderObserver::class);
    }
}

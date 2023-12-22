<?php
namespace Edenlife\Superban;

use Edenlife\Superban\SuperBanService;
use Illuminate\Support\ServiceProvider;
use Edenlife\Superban\Http\Middleware\SuperBanMiddleware;
use Illuminate\Cache\RateLimiter;

class SuperBanServiceProvider extends ServiceProvider{
    
    public function register() : void
    {
        $this->app->singleton('superban', function ($app) {
            $cache = $app->make('cache');
            return new SuperBanService($cache, $app->make(RateLimiter::class));
        });

        $this->registerMiddleware();

        $this->mergeConfigFrom(__DIR__ . '/config/superban.php', 'superban');
    }

    public function boot() : void 
    {
        $this->publishes([
            __DIR__ . '/config/superban.php' => config_path('superban.php'),
        ], 'superban');
    }

    protected function registerMiddleware() : void
    {
        $this->app['router']->aliasMiddleware('superban', SuperBanMiddleware::class);
    }
}
?>
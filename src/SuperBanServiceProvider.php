<?php
namespace Edenlife\Superban;

use Edenlife\Superban\SuperBanService;
use Illuminate\Support\ServiceProvider;
use Edenlife\Superban\Http\Middleware\SuperBanMiddleware;

class SuperBanServiceProvider extends ServiceProvider{
    
    public function register() : void
    {
        $this->app->singleton('superban', function ($app) {
            return new SuperBanService();
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
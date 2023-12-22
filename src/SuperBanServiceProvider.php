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
    }

    public function boot() : void 
    {

    }

    protected function registerMiddleware() : void
    {
        $this->app['router']->aliasMiddleware('superban', SuperBanMiddleware::class);
    }
}
?>
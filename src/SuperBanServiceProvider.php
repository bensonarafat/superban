<?php
namespace Edenlife\Superban;

use Edenlife\Superban\SuperBanService;
use Illuminate\Support\ServiceProvider;

class SuperBanServiceProvider extends ServiceProvider{
    
    public function register() : void
    {
        $this->app->singleton('superban', function ($app) {
            return new SuperBanService();
          });
    }

    public function boot() : void 
    {

    }
}
?>
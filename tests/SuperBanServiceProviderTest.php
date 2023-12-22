<?php
namespace Edenlife\Superban\Tests;

use Orchestra\Testbench\TestCase;
use Edenlife\Superban\SuperBanService;
use Edenlife\Superban\SuperBanServiceProvider;
use Edenlife\Superban\Http\Middleware\SuperBanMiddleware;

class SuperBanServiceProviderTest extends TestCase{

    protected function getPackageProviders($app) : array 
    {
        return [SuperBanServiceProvider::class];
    }

    public function test_service_provider_registers_superban_singleton() : void 
    {
        $this->assertInstanceOf(
            SuperBanService::class,
            $this->app->make('superban')
        );
    }

    public function test_service_provider_registers_middleware() : void
    {
        $routerMiddleware = $this->app['router']->getMiddleware();
        $this->assertArrayHasKey('superban', $routerMiddleware);
        $this->assertEquals(SuperBanMiddleware::class, $routerMiddleware['superban']);
    }
}
?>
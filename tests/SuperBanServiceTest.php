<?php
namespace Edenlife\Superban\Tests;

use Illuminate\Cache\RateLimiter;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Cache;
use Edenlife\Superban\Models\SuperBan;
use Edenlife\Superban\SuperBanService;
use Illuminate\Contracts\Cache\Factory;
use Edenlife\Superban\SuperBanServiceProvider;

class SuperBanServiceTest extends TestCase{
    
    protected function getPackageProviders($app)
    {
        return [SuperBanServiceProvider::class];
    }

    public function test_is_banned_returns_true_when_banned()
    {
        $cacheKey = 'superban:user123:route123';
        $clientIdentifier = 'user123';
        $route = 'route123';
        
        $ban = new SuperBan([
            'client_identifier' => $clientIdentifier,
            'route' => $route,
            'banned_until' => now()->addMinutes(30), // Banned for 30 minutes
        ]);

        Cache::put($cacheKey, $ban, 30);

        $service = new SuperBanService(app(Factory::class), app(RateLimiter::class));

        $result = $service->isBanned($clientIdentifier, $route);

        $this->assertTrue($result);
    }
}
?>
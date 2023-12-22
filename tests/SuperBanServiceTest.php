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

    public function test_is_banned_returns_true_when_banned() : void
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

    public function test_is_banned_returns_false_when_not_banned() : void
    {
        $clientIdentifier = 'user456';
        $route = 'route456';

        $service = new SuperBanService(app(Factory::class), app(RateLimiter::class));

        $result = $service->isBanned($clientIdentifier, $route);

        $this->assertFalse($result);
    }

    public function test_is_attempt_reached_returns_true_when_attempt_reached(): void 
    {
        $clientIdentifier = 'user789';
        $numberOfRequests = 3;
        $timeInterval = 2;
        $service = new SuperBanService(app(Factory::class), app(RateLimiter::class));
        $result = $service->isAttemptReached($clientIdentifier, $numberOfRequests, $timeInterval);

        $this->assertTrue($result);
    }

    public function test_ban_client_creates_ban_in_cache() :void 
    {
        $clientIdentifier = 'userXYZ';
        $route = 'routeXYZ';
        $banDuration = 60;

        $service = new SuperBanService(app(Factory::class), app(RateLimiter::class));

        $service->banClient($clientIdentifier, $route, $banDuration);

        $cacheKey = "superban:$clientIdentifier:$route";

        $ban = Cache::get($cacheKey);

        $this->assertInstanceOf(SuperBan::class, $ban);
        $this->assertEquals($clientIdentifier, $ban->client_identifier);
        $this->assertEquals($route, $ban->route);
    }
}
?>
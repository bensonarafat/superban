<?php 
namespace Edenlife\Superban;

use Illuminate\Cache\RateLimiter;
use Edenlife\Superban\Models\SuperBan;
use Illuminate\Contracts\Cache\Factory;

class SuperBanService {

    public Factory $cache;

    public RateLimiter $rateLimiter;

    public function __construct(Factory $cache, RateLimiter $rateLimiter)
    {
        $this->cache = $cache; 
        $this->rateLimiter = $rateLimiter;
    }
    
    public function isBanned(string $identifier, string $route) : bool 
    {
        $key = $this->getCacheKey($identifier, $route);
        $ban = $this->cache->get($key);
        return $ban && !$ban->isExpired();
        return false;
    }

    public function isAttemptReached(string $clientIdentifier, int $numberOfRequests, int $timeInterval) : bool 
    {
        $key = "superban:limiter:$clientIdentifier";
        return $this->rateLimiter->attempt($key, $numberOfRequests, function(){}, $timeInterval * 60); // convert timeinterval to seconds.
    }

    public function banClient(string $clientIdentifier, string $route, int $banDuration) : void
    {
        $key = $this->getCacheKey($clientIdentifier, $route);
        $superban = new SuperBan([
            'client_identifier' => $clientIdentifier,
            'route' => $route,
            'banned_until' => now()->addMinutes($banDuration),
        ]);
        $this->cache->put($key, $superban, now()->addMinutes($banDuration));
    }

    private function getCacheKey(string $identifier, string $route) : string
    {
        return "superban:$identifier:$route";
    }
}
?>
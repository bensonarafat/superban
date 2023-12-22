<?php 
namespace Edenlife\Superban;

use Illuminate\Cache\RateLimiter;
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

    }

    private function getCacheKey(string $identifier, string $route) : string
    {
        return "superban:$identifier:$route";
    }
}
?>
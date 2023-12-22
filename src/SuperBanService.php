<?php 
namespace Edenlife\Superban;

use Illuminate\Contracts\Cache\Factory;

class SuperBanService {

    public Factory $cache;

    public function __construct(Factory $cache)
    {
        $this->cache = $cache; 
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
        return false;
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
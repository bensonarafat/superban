<?php 
namespace Edenlife\Superban;

class SuperBanService {
    
    public function isBanned(string $identifier, string $route) : bool 
    {
        return false;
    }

    public function isAttemptReached(string $clientIdentifier, int $numberOfRequests, int $timeInterval) : bool 
    {
        return false;
    }

    public function banClient(string $clientIdentifier, string $route, int $banDuration) : void
    {

    }
}
?>
<?php 
namespace Edenlife\Superban\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Edenlife\Superban\SuperBanService;

class SuperBanMiddleware 
{

    protected SuperBanService $superBanService;
    
    public function __construct($superBanService)
    {
        $this->superBanService = $superBanService;
    }

    public function handle(Request $request, Closure $next, string ...$parameters) : Response 
    {
        $route = $request->route()->uri();
        //parse middleware parameters
        $numberOfRequests = $parameters[0] ?? config('superban.requests_number');
        $timeInterval = $parameters[1] ?? config('superban.time_interval');
        $banDuration = $parameters[2] ?? config('superban.ban_duration');

        // get all requests
        $userId = $this->getUserId($request);
        $ipAddress = $this->getIPAddress($request);
        $email = $this->getEmailAddress($request);

        $Identifiers = compact('userId', 'ipAddress', 'email');

        foreach ($Identifiers as $identifier) {

            if(!empty($identifier) &&  $this->superBanService->isBanned($identifier, $route))
            {
                abort(403, 'You are temporarily banned.');
            }

            if(!empty($identifier) && !$this->superBanService->isAttemptReached($identifier, $numberOfRequests, $timeInterval))
            {
                $this->superBanService->banClient($identifier, $route, $banDuration);
            }
        }

        return $next($request);
    }

    private function getEmailAddress(Request $request) : ? string  
    {
        return $request->user()?->email;
    }

    private function getIPAddress(Request $request) : ? string 
    {
        return $request->ip();
    }

    private function getUserId(Request $request) : ? int 
    {
        return $request->user()?->id;
    }
}
?>
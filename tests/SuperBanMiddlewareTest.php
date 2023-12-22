<?php
namespace Edenlife\Superban\Tests;

use Mockery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Orchestra\Testbench\TestCase;
use Edenlife\Superban\SuperBanService;
use Edenlife\Superban\SuperBanServiceProvider;
use Edenlife\Superban\Http\Middleware\SuperBanMiddleware;

class SuperBanMiddlewareTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [
            SuperBanServiceProvider::class, 
        ];
    }

    protected function getEnvironmentSetUp($app) : void
    {
        config(['superban.requests_number' => 5, 'superban.time_interval' => 60, 'superban.ban_duration' => 300]);
    }

    public function test_should_ban_client_when_attempt_limit_is_reached()
    {
        // Mock the SuperBanService
        $superbanServiceMock = Mockery::mock(SuperBanService::class);
        $superbanServiceMock->shouldReceive('isBanned')->andReturn(false);
        $superbanServiceMock->shouldReceive('isAttemptReached')
                             ->andReturnUsing(function ($identifier, $numberOfRequests, $timeInterval) {
                                 return false;
                             });
        $superbanServiceMock->shouldReceive('banClient');

        // Create the middleware instance
        $middleware = new SuperBanMiddleware($superbanServiceMock);

        // Create a request mock
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('route->uri')->andReturn('/some-route');
        $request->shouldReceive('ip')->andReturn('127.0.0.1');
        $request->shouldReceive('user')->andReturn(null);

        $middleware->handle($request, function () {
            return new Response();
        }, '5', '60', '300'); // Pass middleware parameters
       
        $superbanServiceMock->shouldHaveReceived('banClient')->with('127.0.0.1', '/some-route', 300);
    }
}
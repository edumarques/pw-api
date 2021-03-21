<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Env;
use App\Services\GitHubService;
use App\Exceptions\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use App\Exceptions\EnvironmentException;
use Illuminate\Http\Client\PendingRequest;

/**
 * @group  Unit
 * @covers \App\Services\GitHubService
 */
final class GitHubServiceTest extends TestCase
{
    private GitHubService $gitHubService;


    public function testEnvVarIsNotSet(): void
    {
        $appGitHubToken = Env::getRepository()->get('APP_GITHUB_TOKEN');
        Env::getRepository()->set('APP_GITHUB_TOKEN', '');

        $this->expectExceptionObject(new EnvironmentException('GITHUB_TOKEN is not set in .env'));

        new GitHubService();

        Env::getRepository()->set('APP_GITHUB_TOKEN', $appGitHubToken);
    }


    /**
     * @throws RequestException
     */
    public function testGetUserDataWithFailure(): void
    {
        $pendingRequest = \Mockery::mock(PendingRequest::class);

        Http::shouldReceive('withHeaders')
            ->with(
                [
                    'Authorization' => 'token 2de1272ea3f71c0887999a663094d990099dcc16',
                ]
            )->andReturn($pendingRequest);

        $response = new Response(new \GuzzleHttp\Psr7\Response(401));

        $pendingRequest->shouldReceive('get')
            ->with('https://api.github.com/users/edumarques')
            ->andReturn($response);

        $this->expectExceptionObject(new RequestException($response));

        $this->gitHubService->getUserData();
    }


    /**
     * @throws RequestException
     */
    public function testGetUserData(): void
    {
        $pendingRequest = \Mockery::mock(PendingRequest::class);

        Http::shouldReceive('withHeaders')
            ->with(
                [
                    'Authorization' => 'token 2de1272ea3f71c0887999a663094d990099dcc16',
                ]
            )->andReturn($pendingRequest);

        $response = new Response(new \GuzzleHttp\Psr7\Response(200));

        $pendingRequest->shouldReceive('get')
            ->with('https://api.github.com/users/edumarques')
            ->andReturn($response);

        $this->assertSame($response, $this->gitHubService->getUserData());
    }


    /**
     * @throws RequestException
     */
    public function testGetReposDataWithFailure(): void
    {
        $pendingRequest = \Mockery::mock(PendingRequest::class);

        Http::shouldReceive('withHeaders')
            ->with(
                [
                    'Authorization' => 'token 2de1272ea3f71c0887999a663094d990099dcc16',
                ]
            )->andReturn($pendingRequest);

        $response = new Response(new \GuzzleHttp\Psr7\Response(401));

        $pendingRequest->shouldReceive('get')
            ->with('https://api.github.com/users/edumarques/repos')
            ->andReturn($response);

        $this->expectExceptionObject(new RequestException($response));

        $this->gitHubService->getReposData();
    }


    /**
     * @throws RequestException
     */
    public function testGetReposData(): void
    {
        $pendingRequest = \Mockery::mock(PendingRequest::class);

        Http::shouldReceive('withHeaders')
            ->with(
                [
                    'Authorization' => 'token 2de1272ea3f71c0887999a663094d990099dcc16',
                ]
            )->andReturn($pendingRequest);

        $response = new Response(new \GuzzleHttp\Psr7\Response(200));

        $pendingRequest->shouldReceive('get')
            ->with('https://api.github.com/users/edumarques/repos')
            ->andReturn($response);

        $this->assertSame($response, $this->gitHubService->getReposData());
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->gitHubService = new GitHubService();
    }
}

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
    private ?string $originalGitHubToken;

    private ?string $testGitHubToken;


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
        $this->setGitHubToken();

        $this->expectExceptionObject(new EnvironmentException('GITHUB_TOKEN is not set in .env'));

        $gitHubService = new GitHubService();

        $pendingRequest = \Mockery::mock(PendingRequest::class);

        Http::shouldReceive('withHeaders')
            ->with(
                [
                    'Authorization' => 'token ' . $this->testGitHubToken,
                ]
            )->andReturn($pendingRequest);

        $response = new Response(new \GuzzleHttp\Psr7\Response(401));

        $pendingRequest->shouldReceive('get')
            ->with('https://api.github.com/users/edumarques')
            ->andReturn($response);

        $this->expectExceptionObject(new RequestException($response));

        $gitHubService->getUserData();

        $this->restoreGitHubToken();
    }


    /**
     * @throws RequestException
     */
    public function testGetUserData(): void
    {
        $this->setGitHubToken();

        $pendingRequest = \Mockery::mock(PendingRequest::class);

        Http::shouldReceive('withHeaders')
            ->with(
                [
                    'Authorization' => 'token ' . $this->testGitHubToken,
                ]
            )->andReturn($pendingRequest);

        $response = new Response(new \GuzzleHttp\Psr7\Response(200));

        $pendingRequest->shouldReceive('get')
            ->with('https://api.github.com/users/edumarques')
            ->andReturn($response);

        $gitHubService = new GitHubService();

        $this->assertSame($response, $gitHubService->getUserData());

        $this->restoreGitHubToken();
    }


    /**
     * @throws RequestException
     */
    public function testGetReposDataWithFailure(): void
    {
        $this->setGitHubToken();

        $pendingRequest = \Mockery::mock(PendingRequest::class);

        Http::shouldReceive('withHeaders')
            ->with(
                [
                    'Authorization' => 'token ' . $this->testGitHubToken,
                ]
            )->andReturn($pendingRequest);

        $response = new Response(new \GuzzleHttp\Psr7\Response(401));

        $pendingRequest->shouldReceive('get')
            ->with('https://api.github.com/users/edumarques/repos')
            ->andReturn($response);

        $this->expectExceptionObject(new RequestException($response));

        $gitHubService = new GitHubService();

        $gitHubService->getReposData();

        $this->restoreGitHubToken();
    }


    /**
     * @throws RequestException
     */
    public function testGetReposData(): void
    {
        $this->setGitHubToken();

        $pendingRequest = \Mockery::mock(PendingRequest::class);

        Http::shouldReceive('withHeaders')
            ->with(
                [
                    'Authorization' => 'token ' . $this->testGitHubToken,
                ]
            )->andReturn($pendingRequest);

        $response = new Response(new \GuzzleHttp\Psr7\Response(200));

        $pendingRequest->shouldReceive('get')
            ->with('https://api.github.com/users/edumarques/repos')
            ->andReturn($response);

        $gitHubService = new GitHubService();

        $this->assertSame($response, $gitHubService->getReposData());

        $this->restoreGitHubToken();
    }


    private function setGitHubToken(): void
    {
        $this->originalGitHubToken = Env::getRepository()->get('APP_GITHUB_TOKEN');
        $this->testGitHubToken     = uniqid();

        Env::getRepository()->set('APP_GITHUB_TOKEN', $this->testGitHubToken);
    }


    private function restoreGitHubToken(): void
    {
        if ($this->originalGitHubToken !== null) {
            Env::getRepository()->set('APP_GITHUB_TOKEN', $this->originalGitHubToken);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\EnvironmentException;
use App\Exceptions\RequestException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected const USER_URL  = 'https://api.github.com/users/edumarques';
    protected const REPOS_URL = self::USER_URL . '/repos';

    protected ?string $token;


    /**
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->token = env('APP_GITHUB_TOKEN');

        throw_if(empty($this->token), new EnvironmentException('GITHUB_TOKEN is not set in .env'));
    }


    /**
     * @return Response
     * @throws RequestException
     */
    public function getUserData(): Response
    {
        $response = $this->authenticate()->get(self::USER_URL);

        $this->handleFailure($response);

        return $response;
    }


    /**
     * @return Response
     * @throws RequestException
     */
    public function getReposData(): Response
    {
        $response = $this->authenticate()->get(self::REPOS_URL);

        $this->handleFailure($response);

        return $response;
    }


    protected function authenticate(): PendingRequest
    {
        return Http::withHeaders($this->requestHeaders());
    }


    protected function requestHeaders(): array
    {
        return [
            'Authorization' => 'token ' . $this->token,
        ];
    }


    /**
     * @param Response $response
     *
     * @throws RequestException
     */
    protected function handleFailure(Response $response): void
    {
        $response->onError(
            fn(Response $response): RequestException => throw new RequestException($response)
        );
    }
}

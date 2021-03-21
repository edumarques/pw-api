<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\RequestException;

class PortfolioService
{
    protected GitHubService $gitHubService;


    public function __construct(GitHubService $gitHubService)
    {
        $this->gitHubService = $gitHubService;
    }


    /**
     * @return array
     * @throws RequestException
     */
    public function getUserData(): array
    {
        return $this->modelUserData($this->gitHubService->getUserData()->json());
    }


    /**
     * @return array
     * @throws RequestException
     */
    public function getReposData(): array
    {
        return $this->modelReposData($this->gitHubService->getReposData()->json());
    }


    protected function modelUserData(array $data): array
    {
        return [
            'name'               => $data['name'] ?? null,
            'gitHub_login'       => $data['login'] ?? null,
            'gitHub_bio'         => $data['bio'] ?? null,
            'gitHub_repos'       => $data['public_repos'] ?? null,
            'gitHub_profile_url' => $data['html_url'] ?? null,
            'gitHub_avatar_url'  => $data['avatar_url'] ?? null,
            'gitHub_updated_at'  => $data['updated_at'] ?? null,
        ];
    }


    protected function modelReposData(array $data): array
    {
        $modeledData = [];

        foreach ($data as $repoData) {
            $modeledData[] = [
                'name'        => $repoData['name'] ?? null,
                'description' => $repoData['description'] ?? null,
                'url'         => $repoData['html_url'] ?? null,
                'language'    => $repoData['language'] ?? null,
                'created_at'  => $repoData['created_at'] ?? null,
                'updated_at'  => $repoData['updated_at'] ?? null,
            ];
        }

        return $modeledData;
    }
}

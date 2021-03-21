<?php

declare(strict_types=1);

namespace Tests\Functional;

use Tests\TestCase;

/**
 * @group Functional
 */
final class PortfolioTest extends TestCase
{
    public function testThereIsNoRootRoute(): void
    {
        $this->get('/api/v1/portfolio');

        $this->assertResponseStatus(404);
    }


    public function testUserRoute(): void
    {
        $this->get('/api/v1/portfolio/user');

        $this->assertResponseStatus(200);

        $expectedKeys = [
            'name',
            'gitHub_login',
            'gitHub_bio',
            'gitHub_repos',
            'gitHub_profile_url',
            'gitHub_avatar_url',
            'gitHub_updated_at',
        ];

        $responseJson = $this->response->json();

        foreach ($expectedKeys as $expectedKey) {
            $this->assertArrayHasKey($expectedKey, $responseJson);
        }
    }


    public function testReposRoute(): void
    {
        $this->get('/api/v1/portfolio/repos');

        $this->assertResponseStatus(200);

        $expectedKeysInRepo = [
            'name',
            'description',
            'url',
            'language',
            'created_at',
            'updated_at',
        ];

        $responseJson = $this->response->json();
        $repo         = $responseJson[0] ?? [];

        foreach ($expectedKeysInRepo as $expectedKey) {
            $this->assertArrayHasKey($expectedKey, $repo);
        }
    }
}

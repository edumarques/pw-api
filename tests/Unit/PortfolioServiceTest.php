<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GitHubService;
use App\Services\PortfolioService;
use Illuminate\Http\Client\Response;
use App\Exceptions\RequestException;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group  Unit
 * @covers \App\Services\PortfolioService
 */
final class PortfolioServiceTest extends TestCase
{
    private GitHubService|MockObject $gitHubService;

    private PortfolioService $portfolioService;


    /**
     * @dataProvider dataTestGetUserData
     *
     * @param array $expected
     * @param array $responseJson
     *
     * @throws RequestException
     */
    public function testGetUserData(array $expected, array $responseJson): void
    {
        $responseUserData = $this->createMock(Response::class);

        $this->gitHubService->expects($this->once())
            ->method('getUserData')
            ->willReturn($responseUserData);

        $responseUserData->expects($this->once())
            ->method('json')
            ->willReturn($responseJson);

        $this->assertSame($expected, $this->portfolioService->getUserData());
    }


    public function dataTestGetUserData(): \Generator
    {
        yield [
            [
                'name'               => null,
                'gitHub_login'       => null,
                'gitHub_bio'         => null,
                'gitHub_repos'       => null,
                'gitHub_profile_url' => null,
                'gitHub_avatar_url'  => null,
                'gitHub_updated_at'  => null,
            ],
            [],
        ];

        yield [
            [
                'name'               => 'Eduardo Marques',
                'gitHub_login'       => 'edumarques',
                'gitHub_bio'         => 'Just one more curious guy.',
                'gitHub_repos'       => 19,
                'gitHub_profile_url' => 'https://github.com/edumarques',
                'gitHub_avatar_url'  => 'https://avatars.githubusercontent.com/u/7669915?v=4',
                'gitHub_updated_at'  => '2021-03-21T15:05:20Z',
            ],
            [
                'name'         => 'Eduardo Marques',
                'login'        => 'edumarques',
                'bio'          => 'Just one more curious guy.',
                'public_repos' => 19,
                'html_url'     => 'https://github.com/edumarques',
                'avatar_url'   => 'https://avatars.githubusercontent.com/u/7669915?v=4',
                'updated_at'   => '2021-03-21T15:05:20Z',
            ],
        ];
    }


    /**
     * @dataProvider dataTestGetReposData
     *
     * @param array $expected
     * @param array $responseJson
     *
     * @throws RequestException
     */
    public function testGetReposData(array $expected, array $responseJson): void
    {
        $responseReposData = $this->createMock(Response::class);

        $this->gitHubService->expects($this->once())
            ->method('getReposData')
            ->willReturn($responseReposData);

        $responseReposData->expects($this->once())
            ->method('json')
            ->willReturn($responseJson);

        $this->assertSame($expected, $this->portfolioService->getReposData());
    }


    public function dataTestGetReposData(): \Generator
    {
        yield [
            [],
            [],
        ];

        yield [
            [
                [
                    'name'        => 'repo1',
                    'description' => 'Repository 1',
                    'url'         => 'https://github.com/edumarques/repo1',
                    'language'    => 'PHP',
                    'created_at'  => '2021-03-21T15:05:20Z',
                    'updated_at'  => '2021-03-21T15:05:20Z',
                ],
            ],
            [
                [
                    'name'        => 'repo1',
                    'description' => 'Repository 1',
                    'html_url'    => 'https://github.com/edumarques/repo1',
                    'language'    => 'PHP',
                    'created_at'  => '2021-03-21T15:05:20Z',
                    'updated_at'  => '2021-03-21T15:05:20Z',
                ],
            ],
        ];
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->gitHubService = $this->createMock(GitHubService::class);

        $this->portfolioService = new PortfolioService($this->gitHubService);
    }
}

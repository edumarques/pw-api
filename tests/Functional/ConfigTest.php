<?php

declare(strict_types=1);

namespace Tests\Functional;

use Tests\TestCase;

/**
 * @group Functional
 */
final class ConfigTest extends TestCase
{
    public function testThereIsNoRootRoute(): void
    {
        $this->get('/api/v1/config');

        $this->assertResponseStatus(404);
    }


    public function testStatusRoute(): void
    {
        $this->get('/api/v1/config/status');

        $this->assertResponseStatus(200);
        $this->assertSame(['connected' => true], $this->response->json());
    }
}

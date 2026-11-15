<?php

namespace Tests\Feature\Api;

use Tests\ApiTestCase;

class MiddlewareTest extends ApiTestCase
{
    public function test_api_is_throttled_to_sixty_requests_per_minute_per_user()
    {
        for ($i = 1; $i <= 60; $i++) {
            $response = $this->api('GET', 'payment-method', [], [], 'kasir');
            $response->assertStatus(200);
        }
        $this->assertSame('0', (string) $response->headers->get('X-RateLimit-Remaining'));
        $this->assertSame('60', (string) $response->headers->get('X-RateLimit-Limit'));

        $this->api('GET', 'payment-method', [], [], 'kasir')->assertStatus(429);

        // The limit counts per user. Another user is not blocked.
        $this->api('GET', 'payment-method', [], [], 'admin')->assertStatus(200);
    }
}

<?php

namespace Tests\Feature\Api;

use Tests\ApiTestCase;

class AuthTest extends ApiTestCase
{
    public function test_login_with_valid_admin_credentials_returns_a_bearer_token()
    {
        $response = $this->api('POST', 'auth/login', ['username' => 'admin', 'password' => 'admin'], [], null);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
        $response->assertJson(['token_type' => 'bearer', 'expires_in' => 3600]);
        $this->assertSame($response->json('access_token'), $response->headers->get('Authorization'));
        $this->assertSame('Authorization', $response->headers->get('Access-Control-Expose-Headers'));
        $this->assertMatchesSnapshot('login_admin', $response);
    }

    public function test_login_with_valid_kasir_credentials_returns_a_token_for_the_kasir()
    {
        $response = $this->api('POST', 'auth/login', ['username' => 'kasir', 'password' => 'kasir'], [], null);
        $response->assertStatus(200);

        $this->forgetAuthGuards();
        $user = $this->call('GET', '/api/v1/auth/user', [], [], [], [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer '.$response->json('access_token'),
        ]);

        $user->assertStatus(200);
        $user->assertJson(['status' => 'success', 'data' => ['id' => 2, 'username' => 'kasir', 'level' => 'Kasir']]);
    }

    public function test_login_with_wrong_password_returns_401_unauthorized()
    {
        $response = $this->api('POST', 'auth/login', ['username' => 'admin', 'password' => 'salah'], [], null);

        $response->assertStatus(401);
        $this->assertSame(['error' => 'Unauthorized'], $response->json());
    }

    public function test_login_with_unknown_username_returns_401_unauthorized()
    {
        $response = $this->api('POST', 'auth/login', ['username' => 'tidakada', 'password' => 'admin'], [], null);

        $response->assertStatus(401);
        $this->assertSame(['error' => 'Unauthorized'], $response->json());
    }

    public function test_auth_user_returns_the_logged_in_user_without_password()
    {
        $response = $this->api('GET', 'auth/user');

        $response->assertStatus(200);
        $this->assertArrayNotHasKey('password', $response->json('data'));
        $this->assertMatchesSnapshot('user_admin', $response);
    }

    public function test_protected_endpoint_without_token_is_rejected()
    {
        $response = $this->api('GET', 'auth/user', [], [], null);

        $this->assertMatchesSnapshot('user_without_token', [
            'status' => $response->getStatusCode(),
            'message' => $response->json('message'),
        ]);
    }

    public function test_logout_returns_success_and_invalidates_the_token()
    {
        $token = $this->tokenFor('admin');
        $server = ['HTTP_ACCEPT' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer '.$token];

        $this->forgetAuthGuards();
        $logout = $this->call('POST', '/api/v1/auth/logout', [], [], [], $server);

        $logout->assertStatus(200);
        $this->assertSame(['status' => 'success', 'msg' => 'Logged out successfully'], $logout->json());

        $this->forgetAuthGuards();
        $after = $this->call('GET', '/api/v1/auth/user', [], [], [], $server);
        $this->assertNotSame(200, $after->getStatusCode());
    }

    public function test_register_endpoint_exists_and_returns_an_empty_200_response()
    {
        $response = $this->api('POST', 'auth/register', ['username' => 'baru'], [], null);

        $response->assertStatus(200);
        $this->assertSame('', $response->getContent());
    }
}

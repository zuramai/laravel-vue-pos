<?php

namespace Tests\Feature\Api;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\ApiTestCase;

class UserTest extends ApiTestCase
{
    private function validUser(array $override = [])
    {
        return array_merge([
            'name' => 'Kasir Dua',
            'email' => 'kasir2@gmail.com',
            'password' => 'rahasia123',
            'phone' => '0812345',
            'address' => 'Jl. Merdeka',
            'level' => 'Kasir',
            'username' => 'kasirdua',
        ], $override);
    }

    public function test_index_lists_users_newest_first()
    {
        $response = $this->api('GET', 'user');

        $response->assertStatus(200);
        $this->assertSame([3, 2], array_column($response->json('data'), 'id'));
        $this->assertMatchesSnapshot('index', $response);
    }

    public function test_index_search_matches_name()
    {
        $response = $this->api('GET', 'user', ['search' => 'Kas']);

        $this->assertSame([2], array_column($response->json('data'), 'id'));
    }

    public function test_store_valid_user_returns_201_with_the_model_and_hashes_the_password()
    {
        $response = $this->api('POST', 'user', $this->validUser(), ['photo' => $this->fakeImage('user.jpg')]);

        $response->assertStatus(201);
        // The auto increment value is not rolled back between tests. Lock the other fields.
        $body = $response->json();
        $this->assertInternalType('int', $body['id']);
        unset($body['id']);
        $this->assertMatchesSnapshot('store', ['status' => $response->getStatusCode(), 'body' => $body]);
        $this->assertFileExists(public_path('images/users/chartest_user.jpg'));

        $user = User::where('username', 'kasirdua')->firstOrFail();
        $this->assertTrue(Hash::check('rahasia123', $user->password));
    }

    public function test_store_invalid_user_returns_400_with_validator_errors()
    {
        $response = $this->api('POST', 'user', [
            'name' => '', 'email' => 'bukan-email', 'password' => '123',
            'phone' => 'abc', 'address' => '', 'level' => 'Boss',
            'username' => 'abc', 'photo' => 'undefined',
        ]);

        $response->assertStatus(400);
        $this->assertMatchesSnapshot('store_invalid', $response);
    }

    public function test_store_with_existing_username_and_email_returns_400()
    {
        $response = $this->api('POST', 'user', $this->validUser([
            'username' => 'kasir00', 'email' => 'admin@gmail.com',
        ]), ['photo' => $this->fakeImage()]);

        $response->assertStatus(400);
        $this->assertSame(['email'], array_keys($response->json()));
    }

    public function test_show_and_edit_return_the_user()
    {
        $show = $this->api('GET', 'user/2');
        $edit = $this->api('GET', 'user/2/edit');

        $show->assertStatus(200);
        $this->assertSame($show->json(), $edit->json());
        $this->assertMatchesSnapshot('show', $show);
    }

    public function test_show_unknown_id_returns_404()
    {
        $this->api('GET', 'user/999')->assertStatus(404);
        $this->api('GET', 'user/999/edit')->assertStatus(404);
    }

    public function test_update_with_photo_returns_success()
    {
        $response = $this->api('POST', 'user/2', $this->validUser(), ['photo' => $this->fakeImage('kasir.jpg')]);

        $response->assertStatus(200);
        $this->assertSame(['status' => 'success'], $response->json());
        $this->assertFileExists(public_path('images/users/chartest_kasir.jpg'));

        $user = User::find(2);
        $this->assertSame('Kasir Dua', $user->name);
        $this->assertSame('kasirdua', $user->username);
        $this->assertSame('chartest_kasir.jpg', $user->image_name);
    }

    public function test_destroy_deletes_the_user()
    {
        $response = $this->api('DELETE', 'user/2');

        $response->assertStatus(200);
        $this->assertSame(['status' => true], $response->json());
        $this->assertDatabaseMissing('users', ['id' => 2]);
    }

    public function test_destroy_unknown_id_returns_500()
    {
        $this->api('DELETE', 'user/999')->assertStatus(500);
    }

    public function test_unused_create_endpoint_returns_an_empty_200_response()
    {
        $response = $this->api('GET', 'user/create');

        $response->assertStatus(200);
        $this->assertSame('', $response->getContent());
    }
}

<?php

namespace Tests\Feature\Api;

use App\Model\Customer;
use Tests\ApiTestCase;

class CustomerTest extends ApiTestCase
{
    private function validCustomer(array $override = [])
    {
        return array_merge([
            'name' => 'Budi',
            'email' => 'budi@gmail.com',
            'password' => 'undefined',
            'phone' => '0819',
            'alamat' => 'Jl. Mawar',
            'role' => 'Eceran',
            'kota' => 'Bandung',
            'provinsi' => 'Jawa Barat',
        ], $override);
    }

    public function test_index_lists_customers_newest_first()
    {
        $response = $this->api('GET', 'customer');

        $response->assertStatus(200);
        $this->assertSame([3, 2, 1], array_column($response->json('data'), 'id'));
        $this->assertMatchesSnapshot('index', $response);
    }

    public function test_index_search_matches_name()
    {
        $response = $this->api('GET', 'customer', ['search' => 'Ab']);

        $this->assertSame([3, 2], array_column($response->json('data'), 'id'));
    }

    public function test_all_returns_every_customer_sorted_by_name_without_pagination()
    {
        $response = $this->api('GET', 'customer/all', [], [], 'kasir');

        $response->assertStatus(200);
        $this->assertSame(['Abdurrahman', 'Abid Fakhri', 'Walk-in Customer'], array_column($response->json('data'), 'name'));
        $this->assertMatchesSnapshot('all', $response);
    }

    public function test_store_with_photo_creates_the_customer()
    {
        $response = $this->api('POST', 'customer', $this->validCustomer(), ['photo' => $this->fakeImage('budi.jpg')]);

        $response->assertStatus(200);
        $this->assertSame(['status' => true], $response->json());
        $this->assertFileExists(public_path('images/customers/chartest_budi.jpg'));
        $this->assertDatabaseHas('customers', [
            'name' => 'Budi', 'email' => 'budi@gmail.com', 'phone' => '0819', 'alamat' => 'Jl. Mawar',
            'role' => 'Eceran', 'kota' => 'Bandung', 'provinsi' => 'Jawa Barat', 'image_name' => 'chartest_budi.jpg',
        ]);
    }

    public function test_store_without_photo_returns_500_because_image_name_is_not_nullable()
    {
        $response = $this->api('POST', 'customer', $this->validCustomer(['photo' => '']));

        $response->assertStatus(500);
        $this->assertSame(3, Customer::count());
    }

    public function test_unused_resource_endpoints_return_an_empty_200_response()
    {
        foreach ([['GET', 'customer/create'], ['GET', 'customer/1'], ['GET', 'customer/1/edit']] as list($method, $uri)) {
            $response = $this->api($method, $uri);
            $response->assertStatus(200);
            $this->assertSame('', $response->getContent(), $uri);
        }
    }
}

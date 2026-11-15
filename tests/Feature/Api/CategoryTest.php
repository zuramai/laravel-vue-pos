<?php

namespace Tests\Feature\Api;

use App\Model\Category;
use Tests\ApiTestCase;

class CategoryTest extends ApiTestCase
{
    public function test_index_lists_categories_newest_first_with_pagination_meta()
    {
        $response = $this->api('GET', 'category');

        $response->assertStatus(200);
        $this->assertSame(1, $response->json('meta.total'));
        $this->assertSame(10, $response->json('meta.per_page'));
        $this->assertMatchesSnapshot('index', $response);
    }

    public function test_index_search_matches_name_with_like()
    {
        $this->assertSame(1, $this->api('GET', 'category', ['search' => 'Ringan'])->json('meta.total'));
        $this->assertSame(0, $this->api('GET', 'category', ['search' => 'zzz'])->json('meta.total'));
    }

    public function test_index_page_two_is_empty()
    {
        $response = $this->api('GET', 'category', ['page' => 2]);

        $response->assertStatus(200);
        $this->assertSame([], $response->json('data'));
        $this->assertMatchesSnapshot('index_page_2', $response);
    }

    public function test_store_with_photo_creates_category_and_moves_the_file()
    {
        $response = $this->api('POST', 'category', [
            'name' => 'Minuman Dingin',
            'description' => 'Minuman botol',
        ], ['photo' => $this->fakeImage('cat.jpg')]);

        $response->assertStatus(201);
        // The auto increment value is not rolled back between tests. Lock the other fields.
        $body = $response->json();
        $this->assertInternalType('int', $body['id']);
        unset($body['id']);
        $this->assertMatchesSnapshot('store', ['status' => $response->getStatusCode(), 'body' => $body]);
        $this->assertFileExists(public_path('images/categories/chartest_cat.jpg'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Minuman Dingin',
            'slug' => 'minuman-dingin',
            'image_name' => 'chartest_cat.jpg',
        ]);
    }

    public function test_store_without_required_fields_returns_422()
    {
        $response = $this->api('POST', 'category', ['name' => '', 'description' => '', 'photo' => 'undefined']);

        $response->assertStatus(422);
        $this->assertMatchesSnapshot('store_invalid', $response);
    }

    public function test_store_without_photo_returns_422()
    {
        $response = $this->api('POST', 'category', ['name' => 'A', 'description' => 'B']);

        $response->assertStatus(422);
        $this->assertSame(['photo'], array_keys($response->json('errors')));
    }

    public function test_edit_returns_the_category()
    {
        $response = $this->api('GET', 'category/1/edit');

        $response->assertStatus(200);
        $this->assertMatchesSnapshot('edit', $response);
    }

    public function test_edit_unknown_id_returns_404()
    {
        $this->api('GET', 'category/999/edit')->assertStatus(404);
    }

    public function test_update_without_photo_keeps_the_image_and_changes_the_slug()
    {
        $response = $this->api('POST', 'category/1', [
            'name' => 'Makanan Berat',
            'description' => 'Nasi',
            'photo' => '',
        ]);

        $response->assertStatus(200);
        $this->assertSame(['success' => true], $response->json());
        $this->assertDatabaseHas('categories', [
            'id' => 1,
            'name' => 'Makanan Berat',
            'description' => 'Nasi',
            'slug' => 'makanan-berat',
            'image_name' => '6446d4c4-852e-42ec-b814-bcaaa1d0c5b0.jpeg',
        ]);
    }

    public function test_update_with_photo_replaces_the_image_name()
    {
        $response = $this->api('POST', 'category/1', [
            'name' => 'Makanan Ringan',
            'description' => 'X',
        ], ['photo' => $this->fakeImage('cat2.png')]);

        $response->assertStatus(200);
        $this->assertFileExists(public_path('images/categories/chartest_cat2.png'));
        $this->assertSame('chartest_cat2.png', Category::find(1)->image_name);
    }

    public function test_update_through_the_resource_put_route_behaves_the_same()
    {
        $response = $this->api('PUT', 'category/1', ['name' => 'Snack', 'description' => 'Y']);

        $response->assertStatus(200);
        $this->assertSame(['success' => true], $response->json());
        $this->assertSame('snack', Category::find(1)->slug);
    }

    public function test_update_has_no_validation_and_a_missing_description_returns_500()
    {
        $response = $this->api('POST', 'category/1', ['name' => 'Z']);

        $response->assertStatus(500);
        $this->assertSame('Makanan Ringan', Category::find(1)->name);
    }

    public function test_update_unknown_id_returns_500()
    {
        $this->api('POST', 'category/999', ['name' => 'X', 'description' => 'Y'])->assertStatus(500);
    }

    public function test_destroy_deletes_and_returns_a_message()
    {
        $category = Category::create(['name' => 'Kosong', 'description' => 'D', 'slug' => 'kosong', 'image_name' => 'x.jpg']);

        $response = $this->api('DELETE', 'category/'.$category->id);

        $response->assertStatus(200);
        $this->assertSame(['status' => 'true', 'msg' => 'Kategori Kosong Berhasil dihapus'], $response->json());
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_destroy_category_that_still_has_products_returns_500()
    {
        $response = $this->api('DELETE', 'category/1');

        $response->assertStatus(500);
        $this->assertDatabaseHas('categories', ['id' => 1]);
    }

    public function test_destroy_unknown_id_returns_500()
    {
        $this->api('DELETE', 'category/999')->assertStatus(500);
    }

    public function test_unused_resource_endpoints_return_an_empty_200_response()
    {
        foreach (['category/create', 'category/1'] as $uri) {
            $response = $this->api('GET', $uri);
            $response->assertStatus(200);
            $this->assertSame('', $response->getContent(), $uri);
        }
    }
}

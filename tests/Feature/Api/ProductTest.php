<?php

namespace Tests\Feature\Api;

use App\Model\Product;
use Tests\ApiTestCase;

class ProductTest extends ApiTestCase
{
    private function validProduct(array $override = [])
    {
        return array_merge([
            'name' => 'Teh Botol',
            'description' => 'Teh manis',
            'code' => 'TEH01',
            'category' => '1',
            'price' => '4000',
            'ppn' => '10',
            'stock' => '50',
        ], $override);
    }

    public function test_index_lists_products_newest_first()
    {
        $response = $this->api('GET', 'product');

        $response->assertStatus(200);
        $this->assertSame([2, 1], array_column($response->json('data'), 'id'));
        $this->assertMatchesSnapshot('index', $response);
    }

    public function test_index_search_matching_both_name_and_code_finds_the_product()
    {
        $response = $this->api('GET', 'product', ['search' => 'TestPPN']);

        $this->assertSame([1], array_column($response->json('data'), 'id'));
    }

    public function test_kasir_search_returns_up_to_five_products_with_discount_sorted_by_name()
    {
        $response = $this->api('GET', 'product/search', ['search' => 'cho'], [], 'kasir');

        $response->assertStatus(200);
        $this->assertSame(['Chocolatos'], array_column($response->json(), 'name'));
        $this->assertMatchesSnapshot('search_cho', $response);
    }

    public function test_kasir_search_by_code_uses_or()
    {
        $response = $this->api('GET', 'product/search', ['search' => 'CHO1'], [], 'kasir');

        $this->assertSame(['Chocolatos'], array_column($response->json(), 'name'));
    }

    public function test_kasir_search_with_empty_term_returns_all_products()
    {
        $response = $this->api('GET', 'product/search', ['search' => ''], [], 'kasir');

        $this->assertSame(['Chocolatos', 'TestPPN'], array_column($response->json(), 'name'));
    }

    public function test_store_with_photo_creates_the_product()
    {
        $response = $this->api('POST', 'product', $this->validProduct(), ['photo' => $this->fakeImage('teh.jpg')]);

        $response->assertStatus(200);
        $this->assertSame(['status' => true], $response->json());
        $this->assertFileExists(public_path('images/products/chartest_teh.jpg'));

        $product = Product::where('code', 'TEH01')->firstOrFail();
        $this->assertSame('teh-botol', $product->slug);
        $this->assertSame('chartest_teh.jpg', $product->image_name);
        $this->assertEquals(4000, $product->price);
        $this->assertEquals(10, $product->ppn);
        $this->assertEquals(50, $product->stock);
        $this->assertEquals(1, $product->category_id);
        $this->assertRegExp('/^[0-9a-f-]{36}$/', $product->uuid);
    }

    public function test_store_with_duplicate_code_returns_422()
    {
        $response = $this->api('POST', 'product', $this->validProduct(['code' => 'CHO1']), ['photo' => $this->fakeImage()]);

        $response->assertStatus(422);
        $this->assertMatchesSnapshot('store_duplicate_code', $response);
    }

    public function test_store_with_invalid_fields_returns_422()
    {
        $response = $this->api('POST', 'product', [
            'name' => '', 'description' => '', 'code' => '', 'category' => '99',
            'price' => 'abc', 'ppn' => '', 'stock' => '', 'photo' => '',
        ]);

        $response->assertStatus(422);
        $this->assertMatchesSnapshot('store_invalid', $response);
    }

    public function test_edit_returns_the_product()
    {
        $response = $this->api('GET', 'product/2/edit');

        $response->assertStatus(200);
        $this->assertMatchesSnapshot('edit', $response);
    }

    public function test_edit_unknown_id_returns_404()
    {
        $this->api('GET', 'product/999/edit')->assertStatus(404);
    }

    public function test_update_without_photo_changes_fields_but_not_slug_or_ppn()
    {
        $response = $this->api('POST', 'product/2', [
            'name' => 'Chocolatos Besar',
            'description' => 'Baru',
            'code' => 'CHO2',
            'category' => '1',
            'price' => '700',
            'stock' => '100',
            'photo' => '23a5ae9957741bb83da2e4201c8b44ce.jpeg',
        ]);

        $response->assertStatus(200);
        $this->assertSame(['status' => true], $response->json());

        $product = Product::find(2);
        $this->assertSame('Chocolatos Besar', $product->name);
        $this->assertSame('CHO2', $product->code);
        $this->assertEquals(700, $product->price);
        $this->assertEquals(100, $product->stock);
        $this->assertSame('chocolatos', $product->slug);
        $this->assertEquals(20, $product->ppn);
        $this->assertSame('23a5ae9957741bb83da2e4201c8b44ce.jpeg', $product->image_name);
    }

    public function test_update_with_photo_replaces_the_image()
    {
        $response = $this->api('POST', 'product/2', [
            'name' => 'Chocolatos', 'description' => 'D', 'code' => 'CHO1',
            'category' => '1', 'price' => '500', 'stock' => '155',
        ], ['photo' => $this->fakeImage('choco.png')]);

        $response->assertStatus(200);
        $this->assertFileExists(public_path('images/products/chartest_choco.png'));
        $this->assertSame('chartest_choco.png', Product::find(2)->image_name);
    }

    public function test_update_with_invalid_fields_returns_422()
    {
        $response = $this->api('POST', 'product/2', ['name' => '', 'price' => 'x']);

        $response->assertStatus(422);
        $this->assertMatchesSnapshot('update_invalid', $response);
    }

    public function test_destroy_soft_deletes_the_product()
    {
        $response = $this->api('DELETE', 'product/1');

        $response->assertStatus(200);
        $this->assertSame(['status' => true], $response->json());
        $this->assertSoftDeleted('products', ['id' => 1]);
        $this->assertSame([2], array_column($this->api('GET', 'product')->json('data'), 'id'));
    }

    public function test_destroy_unknown_id_returns_500()
    {
        $this->api('DELETE', 'product/999')->assertStatus(500);
    }

    public function test_unused_resource_endpoints_return_an_empty_200_response()
    {
        foreach (['product/create', 'product/1'] as $uri) {
            $response = $this->api('GET', $uri);
            $response->assertStatus(200);
            $this->assertSame('', $response->getContent(), $uri);
        }
    }
}

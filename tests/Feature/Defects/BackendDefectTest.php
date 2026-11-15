<?php

namespace Tests\Feature\Defects;

use App\Model\Category;
use App\Model\Customer;
use App\Model\Product;
use App\Model\Transaction;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\ApiTestCase;
use Tests\Feature\Api\TransactionTest;

/**
 * Characterization tests that lock the CURRENT, WRONG behavior of known defects.
 *
 * The refactoring must not change these results. Each test name carries the
 * defect ID of docs/evidence/defect-log.md. A test that turns red means that
 * a refactoring changed the behavior of a defect.
 *
 * @group defect
 */
class BackendDefectTest extends ApiTestCase
{
    private function checkout(array $cart, array $extra = [])
    {
        return $this->apiJson('POST', 'transaction/new', array_merge([
            'kembalian' => 0,
            'bayar' => 200000,
            'cart' => $cart,
            'customer' => 1,
            'payment_method' => 1,
        ], $extra), 'kasir');
    }

    /** @group defect */
    public function test_defect_D01_checkout_reduces_stock_of_the_last_cart_item_only()
    {
        $this->checkout([TransactionTest::cartLine(1, 1), TransactionTest::cartLine(2, 2)])->assertStatus(200);

        $this->assertSame(2, (int) Product::find(1)->stock, 'First item: stock is not reduced.');
        $this->assertSame(153, (int) Product::find(2)->stock, 'Last item: stock is reduced.');
    }

    /** @group defect */
    public function test_defect_D01_checkout_with_reversed_cart_reduces_the_other_product()
    {
        $this->checkout([TransactionTest::cartLine(2, 2), TransactionTest::cartLine(1, 1)])->assertStatus(200);

        $this->assertSame(1, (int) Product::find(1)->stock);
        $this->assertSame(155, (int) Product::find(2)->stock);
    }

    /** @group defect */
    public function test_defect_D02_checkout_without_customer_fails_with_500_instead_of_walk_in_customer()
    {
        $before = Transaction::count();

        $this->checkout([TransactionTest::cartLine(2, 1)], ['customer' => ''])->assertStatus(500);
        $this->checkout([TransactionTest::cartLine(2, 1)], ['customer' => null])->assertStatus(500);

        $this->assertSame($before, Transaction::count());
    }

    /** @group defect */
    public function test_defect_D03_growth_percentage_divides_by_one_instead_of_last_month()
    {
        // December 2019: 0 orders, November 2019: 13 orders. Correct value: -100%.
        $this->freezeTime('2019-12-10 12:00:00');

        $body = $this->api('GET', 'admin/home')->json();

        $this->assertSame(0, $body['transaksi_bulan_ini']);
        $this->assertSame(13, $body['transaksi_bulan_lalu']);
        $this->assertSame('-1300%', $body['peningkatan_transaksi']);
        $this->assertSame('-2130000%', $body['peningkatan_pendapatan']);
    }

    /** @group defect */
    public function test_defect_D04_customer_detail_update_and_delete_endpoints_do_nothing()
    {
        foreach ([['GET', 'customer/2'], ['GET', 'customer/2/edit'], ['POST', 'customer/2'], ['PUT', 'customer/2'], ['DELETE', 'customer/2']] as list($method, $uri)) {
            $response = $this->api($method, $uri, ['name' => 'Baru']);
            $response->assertStatus(200);
            $this->assertSame('', $response->getContent(), "$method $uri");
        }

        $this->assertSame('Abdurrahman', Customer::find(2)->name);
    }

    /** @group defect */
    public function test_defect_D04_customer_page_delete_button_deletes_the_user_with_the_same_id()
    {
        // The customer page calls DELETE /user/{customer.id}.
        $this->api('DELETE', 'user/2')->assertStatus(200);

        $this->assertNull(User::find(2), 'User "kasir" (id 2) is deleted.');
        $this->assertNotNull(Customer::find(2), 'Customer id 2 still exists.');
    }

    /** @group defect */
    public function test_defect_D04_customer_page_detail_button_shows_the_user_with_the_same_id()
    {
        $response = $this->api('GET', 'user/3');

        $this->assertSame('admin', $response->json('username'));
        $this->assertNotSame(Customer::find(3)->name, $response->json('name'));
    }

    /** @group defect */
    public function test_defect_D05_user_update_saves_the_password_in_plain_text_without_validation()
    {
        $response = $this->api('POST', 'user/2', [
            'name' => 'Kasir',
            'email' => 'bukan-email',
            'password' => 'abc',
            'phone' => 'bukan-angka',
            'address' => 'A',
            'level' => 'Kasir',
            'username' => 'admin',
        ], ['photo' => $this->fakeImage('d05.jpg')]);

        $response->assertStatus(200);
        $this->assertSame(['status' => 'success'], $response->json());

        $raw = DB::table('users')->where('id', 2)->first();
        $this->assertSame('abc', $raw->password, 'Password is stored without hash.');
        $this->assertSame('bukan-email', $raw->email, 'Invalid email is accepted.');
        $this->assertSame(2, User::where('username', 'admin')->count(), 'Duplicate username is accepted.');
        $this->assertFalse(Hash::check('abc', $raw->password));
    }

    /** @group defect */
    public function test_defect_D05_user_update_from_the_ui_stores_the_string_undefined_as_password_and_blocks_login()
    {
        // The edit form never fills "password". FormData sends the string "undefined".
        $this->api('POST', 'user/2', [
            'name' => 'Kasir', 'email' => 'kasir@gmail.com', 'password' => 'undefined', 'phone' => '07123123',
            'address' => 'Kasir12323', 'level' => 'Kasir', 'username' => 'kasir',
        ], ['photo' => $this->fakeImage('d05b.jpg')])->assertStatus(200);

        $this->assertSame('undefined', DB::table('users')->where('id', 2)->value('password'));
        $this->api('POST', 'auth/login', ['username' => 'kasir', 'password' => 'kasir'], [], null)->assertStatus(401);
        $this->api('POST', 'auth/login', ['username' => 'kasir', 'password' => 'undefined'], [], null)->assertStatus(401);
    }

    /** @group defect */
    public function test_defect_D07_product_search_requires_name_and_code_to_match()
    {
        $this->assertSame(0, $this->api('GET', 'product', ['search' => 'Choco'])->json('meta.total'), 'Name only.');
        $this->assertSame(0, $this->api('GET', 'product', ['search' => 'CHO1'])->json('meta.total'), 'Code only.');
        $this->assertSame(1, $this->api('GET', 'product', ['search' => 'TestPPN'])->json('meta.total'), 'Name and code.');
    }

    /** @group defect */
    public function test_defect_D09_cors_middleware_does_not_send_allow_origin_and_allow_methods()
    {
        $response = $this->api('GET', 'payment-method');

        $this->assertFalse($response->headers->has('Access-Control-Allow-Origin'));
        $this->assertFalse($response->headers->has('Access-Control-Allow-Methods'));
        $this->assertSame('Authorization', $response->headers->get('Access-Control-Expose-Headers'));
        $this->assertSame(
            'Origin, X-Requested-With, Content-Type, Accept, Authorization',
            $response->headers->get('Access-Control-Allow-Headers')
        );
    }

    /** @group defect */
    public function test_defect_D10_customer_store_ignores_the_validator()
    {
        $invalidEmail = $this->api('POST', 'customer', [
            'name' => 'C', 'email' => 'bukan-email', 'phone' => '1', 'alamat' => 'A',
            'role' => 'Retail', 'kota' => 'K', 'provinsi' => 'P',
        ], ['photo' => $this->fakeImage('d10.jpg')]);
        $invalidEmail->assertStatus(200);
        $this->assertDatabaseHas('customers', ['email' => 'bukan-email']);

        $empty = $this->api('POST', 'customer', [
            'name' => '', 'email' => '', 'phone' => '', 'alamat' => '', 'role' => '', 'kota' => '', 'provinsi' => '',
        ], ['photo' => $this->fakeImage('d10b.jpg')]);
        $empty->assertStatus(500);
    }

    /** @group defect */
    public function test_defect_D11_report_this_month_ignores_the_year()
    {
        // November 2020: no orders in that month. The 13 orders are from November 2019.
        $this->freezeTime('2020-11-10 12:00:00');

        $total = $this->api('GET', 'report')->json('total');

        $this->assertSame(13, $total['transaction_thismo']);
        $this->assertSame(15, $total['product_sold_thismo']);
    }

    /** @group defect */
    public function test_defect_D12_product_store_without_photo_fails_with_500()
    {
        $response = $this->api('POST', 'product', [
            'name' => 'Tanpa Foto', 'description' => 'D', 'code' => 'NOFOTO', 'category' => '1',
            'price' => '1000', 'ppn' => '10', 'stock' => '1', 'photo' => '',
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseMissing('products', ['code' => 'NOFOTO']);
    }

    /** @group defect */
    public function test_defect_D15_siswa_controller_has_no_route()
    {
        foreach (Route::getRoutes() as $route) {
            $this->assertNotContains('SiswaController', $route->getActionName());
        }
    }

    /** @group defect */
    public function test_defect_D16_discount_is_shown_to_the_kasir_but_not_applied_to_the_price()
    {
        $search = $this->api('GET', 'product/search', ['search' => 'CHO1'], [], 'kasir')->json();
        $this->assertEquals(20, $search[0]['discount']['amount']);

        $this->checkout([TransactionTest::cartLine(2, 10)])->assertStatus(200);

        $order = Transaction::with('details')->orderBy('id', 'desc')->first();
        $this->assertEquals(600, $order->details[0]->price, '500 + 20% PPN, no discount.');
        $this->assertEquals(6000, $order->total);
    }

    /** @group defect */
    public function test_defect_D17_token_refresh_does_not_work()
    {
        $post = $this->api('POST', 'auth/refresh', [], [], 'admin');
        $post->assertStatus(200);
        $this->assertSame('', $post->getContent());

        // The UI calls GET. No API route matches, the SPA catch-all route answers with HTML.
        $get = $this->api('GET', 'auth/refresh', [], [], 'admin');
        $get->assertStatus(200);
        $this->assertContains('<html', strtolower($get->getContent()));
        $this->assertNull(json_decode($get->getContent()));
    }

    /** @group defect */
    public function test_defect_D18_upload_with_the_same_file_name_overwrites_the_older_file()
    {
        $this->api('POST', 'category', ['name' => 'A', 'description' => 'A'], ['photo' => $this->fakeImage('same.jpg')])->assertStatus(201);
        $first = file_get_contents(public_path('images/categories/chartest_same.jpg'));

        $second = \Illuminate\Http\UploadedFile::fake()->image(static::UPLOAD_PREFIX.'same.jpg', 40, 40);
        $this->api('POST', 'category', ['name' => 'B', 'description' => 'B'], ['photo' => $second])->assertStatus(201);

        $this->assertNotSame($first, file_get_contents(public_path('images/categories/chartest_same.jpg')));
        $this->assertSame(2, Category::where('image_name', 'chartest_same.jpg')->count());
    }

    /** @group defect */
    public function test_defect_D19_dashboard_income_is_the_sum_of_ppn_not_sales()
    {
        $this->freezeTime('2019-11-25 12:00:00');

        $body = $this->api('GET', 'admin/home')->json();

        $sumPpn = (float) DB::table('order_details')->where('created_at', 'LIKE', '%2019-11%')->sum('ppn');
        $sumSales = (float) DB::table('order_details')->where('created_at', 'LIKE', '%2019-11%')->sum('subtotal');
        $this->assertEquals($sumPpn, $body['pendapatan_bulan_ini']);
        $this->assertEquals(21300, $body['pendapatan_bulan_ini']);
        $this->assertNotEquals($sumSales, $body['pendapatan_bulan_ini']);
    }

    /** @group defect */
    public function test_defect_D21_user_update_without_a_new_photo_fails_with_500()
    {
        // The edit form sends photo = "" when the admin does not pick a new file.
        $response = $this->api('POST', 'user/2', [
            'name' => 'Kasir Baru', 'email' => 'kasir@gmail.com', 'password' => 'undefined', 'phone' => '07123123',
            'address' => 'Kasir12323', 'level' => 'Kasir', 'username' => 'kasir', 'photo' => '',
        ]);

        $response->assertStatus(500);
        $this->assertSame('Kasir', User::find(2)->name);
    }

    /** @group defect */
    public function test_defect_D22_deleting_a_category_that_still_has_products_fails_with_500()
    {
        $response = $this->api('DELETE', 'category/1');

        $response->assertStatus(500);
        $this->assertNotNull(Category::find(1));
    }

    /** @group defect */
    public function test_defect_D23_checkout_with_payment_of_one_million_or_more_fails_with_500()
    {
        // orders.bayar, total and kembalian are DOUBLE(8,2): the maximum is 999999.99.
        $this->checkout([TransactionTest::cartLine(2, 1)], ['bayar' => 999999.99])->assertStatus(200);

        $before = Transaction::count();
        $this->checkout([TransactionTest::cartLine(2, 1)], ['bayar' => 1000000])->assertStatus(500);
        $this->assertSame($before, Transaction::count());
    }

    /** @group defect */
    public function test_defect_D23_checkout_with_a_total_of_one_million_or_more_fails_with_500()
    {
        // 10 x TestPPN = 1,050,000. The order row is created first, the total update fails.
        $line = TransactionTest::cartLine(1, 10);
        $line['stock'] = 100;

        $this->checkout([$line], ['bayar' => 999999])->assertStatus(500);
    }
}

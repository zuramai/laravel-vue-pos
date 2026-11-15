<?php

namespace Tests\Feature\Api;

use Tests\ApiTestCase;

class DashboardReportTest extends ApiTestCase
{
    public function test_admin_home_in_a_month_without_orders()
    {
        $response = $this->api('GET', 'admin/home');

        $response->assertStatus(200);
        $this->assertSame(0, $response->json('transaksi_bulan_ini'));
        $this->assertMatchesSnapshot('home_2026_03', $response);
    }

    public function test_admin_home_in_november_2019_with_seed_orders()
    {
        $this->freezeTime('2019-11-25 12:00:00');

        $response = $this->api('GET', 'admin/home');

        $response->assertStatus(200);
        $this->assertMatchesSnapshot('home_2019_11', $response);
    }

    public function test_admin_home_in_december_2019_when_sales_drop()
    {
        $this->freezeTime('2019-12-10 12:00:00');

        $response = $this->api('GET', 'admin/home');

        $response->assertStatus(200);
        $this->assertMatchesSnapshot('home_2019_12', $response);
    }

    public function test_admin_home_in_february_2020()
    {
        $this->freezeTime('2020-02-20 12:00:00');

        $response = $this->api('GET', 'admin/home');

        $response->assertStatus(200);
        $this->assertMatchesSnapshot('home_2020_02', $response);
    }

    public function test_report_totals_and_seven_day_chart()
    {
        $this->freezeTime('2019-11-25 12:00:00');

        $response = $this->api('GET', 'report');

        $response->assertStatus(200);
        $this->assertSame(15, $response->json('total.transaction'));
        $this->assertSame(1, $response->json('total.category'));
        $this->assertSame(2, $response->json('total.product'));
        $this->assertSame(3, $response->json('total.customer'));
        $this->assertSame(17, $response->json('total.product_sold'));
        $this->assertMatchesSnapshot('report_2019_11', $response);
    }

    public function test_report_in_a_month_without_orders()
    {
        $response = $this->api('GET', 'report');

        $response->assertStatus(200);
        $this->assertMatchesSnapshot('report_2026_03', $response);
    }

    /**
     * @dataProvider pdfReports
     */
    public function test_pdf_report_writes_the_file_and_returns_its_name($uri, $filename)
    {
        $path = public_path('pdf/'.$filename);
        file_put_contents($path, 'old');

        $response = $this->api('GET', $uri);

        $response->assertStatus(200);
        $this->assertSame(['status' => true, 'filename' => $filename], $response->json());
        $this->assertStringStartsWith('%PDF-', file_get_contents($path));
    }

    public function pdfReports()
    {
        return [
            'transaction' => ['report/pdf_transaction', 'transaction.pdf'],
            'products' => ['report/pdf_products', 'products.pdf'],
            'users' => ['report/pdf_users', 'users.pdf'],
        ];
    }
}

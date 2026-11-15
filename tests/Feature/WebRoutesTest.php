<?php

namespace Tests\Feature;

use Tests\TestCase;

class WebRoutesTest extends TestCase
{
    /**
     * @dataProvider spaPages
     */
    public function test_every_web_path_returns_the_spa_shell($uri, $marker)
    {
        $response = $this->get($uri);

        $response->assertStatus(200);
        $this->assertContains($marker, $response->getContent());
    }

    public function spaPages()
    {
        return [
            'root' => ['/', 'js/app.js'],
            'admin' => ['/admin/product', 'js/app.js'],
            'kasir invoice' => ['/transaksi/invoice/abc', 'js/app.js'],
            'login' => ['/auth/login', 'js/app.js'],
        ];
    }
}

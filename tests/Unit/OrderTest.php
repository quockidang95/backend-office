<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_can_create_order()
    {
        $data = [
            'store_code' => 'CH53MT',
            'table' => 'T07',
            'total_price' => 50000,
            'price' => 45000,
            'products' => '[{"id":3,"image":"bacxiu69.jpeg","name":"BẠC XỈU ĐÁ","price":25000,"price_L":0,"promotion_price":0,"recipe":[],"slChon":2,"status":0}]'
        ];

        $response = $this->json('POST', '/api/orders', $data);

        $response->assertStatus(200);
    }
}

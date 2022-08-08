<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{

    public function test_order_creation_and_update()
    {
        $response = $this->json('post', route('order.store'));
        $response->assertStatus(400);
    }
}

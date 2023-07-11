<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelTest extends TestCase
{
    use RefreshDatabase;

    public function test_travel_return_just_public_travel(): void
    {
        Travel::factory(15)->create(['is_public' => true]);
        Travel::factory(15)->create(['is_public' => false]);

        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);

        $response->assertJsonCount(15);
    }
}

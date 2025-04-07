<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeatherApiTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_can_search_weather()
    {
        Http::fake([
            '*' => Http::response([
                'location'=> ['localtime' => '2025-04-06 4:00'],
                'current' => [
                    'temp_c' => 20,
                    'condition' => ['text' => 'Sunny'],
                    'wind_kph' => 10,
                    'humidity' => 60,
                ]
            ], 200)
        ]);

        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->getJson('api/auth/weather?city=Bogota');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'temperature',
            'condition',
            'wind_kph',
            'humidity',
            'local_time',
        ]);
    }

    public function test_user_cannot_access_weather_routes()
    {
        $response = $this->getJson('api/auth/weather?city=Bogota');
        $response->assertStatus(401);
    }
}

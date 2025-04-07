<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;

class WeatherServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_weather_data_returns_expected_structure()
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

        $service = new WeatherService();
        $data = $service->getWeatherByCity('Bogota');

        $this->assertArrayHasKey('temperature', $data);
        $this->assertArrayHasKey('condition', $data);
        $this->assertArrayHasKey('wind_kph', $data);
        $this->assertArrayHasKey('humidity', $data);
        $this->assertArrayHasKey('local_time', $data);
        $this->assertEquals('Sunny', $data['condition']);
        $this->assertEquals(20, $data['temperature']);
    }
}

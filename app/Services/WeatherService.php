<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected string $baseUrl = 'http://api.weatherapi.com/v1';

    public function getWeatherByCity(string $city)
    {
        $cacheWeatherKey = 'weather' . strtolower($city);
        // dd(config('services.weatherapi.key'));
        return Cache::remember($cacheWeatherKey, now()->addMinutes(10), function () use ($city) {
            $response = Http::get(
                "{$this->baseUrl}/current.json",
                [
                    'key' => config('services.weatherapi.key'),
                    'q' => $city,
                    'aqi' => 'no'
                ]
            );

            if($response->failed())
            {
                throw new Exception(__('weather.error_city',['city' => $city]));
            }

            $data = $response->json();
            return [
                'local_time' => $data['location']['localtime'],
                'temperature' => $data['current']['temp_c'],
                'condition' => $data['current']['condition']['text'],
                'wind_kph' => $data['current']['wind_kph'],
                'humidity' => $data['current']['humidity'],
            ];
        }); 
    }
}
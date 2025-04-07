<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WeatherSearch;
use CreateWeatherSearchesTable;
use App\Services\WeatherService;
use App\Http\Controllers\Controller;

class WeatherController extends Controller
{
    protected $weather;

    public function __construct(WeatherService $weatherService)
    {
        $this->weather = $weatherService;
    }
/**
 * @OA\Get(
 *     path="/api/auth/weather",
 *     summary="Consultar clima por ciudad",
 *     tags={"Clima"},
 *     security={{"BearerAuth":{}}},
 *     @OA\Parameter(
 *         name="city",
 *         in="query",
 *         required=true,
 *         description="Nombre de la ciudad",
 *         @OA\Schema(type="string", example="Bogotá")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Datos del clima obtenidos correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="temperature", type="number", example=20),
 *             @OA\Property(property="condition", type="string", example="Sunny"),
 *             @OA\Property(property="wind_kph", type="number", example=10),
 *             @OA\Property(property="humidity", type="number", example=60),
 *             @OA\Property(property="local_time", type="string", example="2025-04-07 10:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Ciudad no encontrada o error externo"
 *     )
 * )
 */

    public function getWeather(Request $request)
    {
        $city = $request->query('city');
        if(!$city)
        {
            return response()->json(['error' => 'Required Parameter'], 400);
        }
        try {
            $data = $this->weather->getWeatherByCity($city);

            //save history data for user
            WeatherSearch::create([
                'user_id' => $request->user()->id,
                'city' => $city,
                'condition' => $data['condition'],
                'temperature' => $data['temperature'],
                'humidity' => $data['humidity'],
                'wind_kph' => $data['wind_kph'],
                'local_time' => $data['local_time'],
            ]);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return $this->weather->getWeatherByCity($city);
    }
/**
 * @OA\Get(
 *     path="/api/auth/history",
 *     summary="Obtener historial de búsquedas del usuario",
 *     tags={"Historial"},
 *     security={{"BearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de búsquedas realizadas",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="city", type="string", example="Bogotá"),
 *                 @OA\Property(property="created_at", type="string", example="2025-04-07T12:00:00.000000Z"),
 *                 @OA\Property(property="is_favorite", type="boolean", example=true)
 *             )
 *         )
 *     )
 * )
 */

    public function history(Request $request)
    {
        $history = WeatherSearch::where('user_id', $request->user()->id)->latest()->take(10)->get();
        return response()->json($history);
    }
/**
 * @OA\Post(
 *     path="/api/auth/favorites/{id}",
 *     summary="Marcar o desmarcar una búsqueda como favorita",
 *     tags={"Favoritos"},
 *     security={{"BearerAuth":{}}},
 *     @OA\Parameter(
 *         name="search_id",
 *         in="path",
 *         required=true,
 *         description="ID de la búsqueda",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Estado actualizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Ciudad marcada como favorita")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Búsqueda no encontrada"
 *     )
 * )
 */

    public function toggleFavorite(Request $request, $id)
    {
        $search = WeatherSearch::where('user_id', $request->user()->id)->findOrFail($id);
        $search->is_favorite = !$search->is_favorite;
        $search->save();

        return response()->json([
            'message' => $search->is_favorite ? 'Marked as a favorite' : 'Dismarked as a favorite',
            'search' => $search,
        ]);
    }
/**
 * @OA\Get(
 *     path="/api/auth/favorites",
 *     summary="Obtener ciudades favoritas del usuario",
 *     tags={"Favoritos"},
 *     security={{"BearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de búsquedas marcadas como favoritas",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=2),
 *                 @OA\Property(property="city", type="string", example="Medellín"),
 *                 @OA\Property(property="created_at", type="string", example="2025-04-07T14:00:00.000000Z"),
 *                 @OA\Property(property="is_favorite", type="boolean", example=true)
 *             )
 *         )
 *     )
 * )
 */

    public function favorites(Request $request)
    {
        $favorites = WeatherSearch::where('user_id', $request->user()->id)->where('is_favorite', true)->latest()->take(10)->get();
        return response()->json($favorites);
    }
}

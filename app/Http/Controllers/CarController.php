<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;

/**
 * @OA\Schema(
 *     schema="Car",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="brand", type="string", example="Toyota"),
 *     @OA\Property(property="model", type="string", example="Corolla"),
 *     @OA\Property(property="year", type="integer", example=2021),
 *     @OA\Property(property="color", type="string", example="Blue"),
 *     @OA\Property(property="license_plate", type="string", example="XYZ-1234"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )
 */
class CarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/auth/cars",
     *     summary="List cars",
     *     description="Get a paginated list of cars",
     *     tags={"Cars"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Number of cars per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="currentPage",
     *         in="query",
     *         description="Current page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="cars",
     *                 type="object",
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Car")),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $currentPage = $request->query('currentPage', 1);

        $cars = Car::paginate($perPage, ['*'], 'page', $currentPage);

        return response()->json([
            'status' => 'success',
            'cars' => $cars,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/cars",
     *     summary="Create car",
     *     description="Create a new car",
     *     tags={"Cars"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"brand","model","year","color","license_plate"},
     *             @OA\Property(property="brand", type="string", example="Toyota"),
     *             @OA\Property(property="model", type="string", example="Corolla"),
     *             @OA\Property(property="year", type="integer", example=2021),
     *             @OA\Property(property="color", type="string", example="Blue"),
     *             @OA\Property(property="license_plate", type="string", example="XYZ-1234")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Car created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Car created successfully"),
     *             @OA\Property(property="car", ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation error")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer',
            'color' => 'required|string|max:255',
            'license_plate' => 'required|string|max:255',
        ]);

        $car = Car::create([
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'color' => $request->color,
            'license_plate' => $request->license_plate,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Car created successfully',
            'car' => $car,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/cars/{id}",
     *     summary="Get car",
     *     description="Get a car by ID",
     *     tags={"Cars"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the car",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="car", ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Car not found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'status' => 'error',
                'message' => 'Car not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'car' => $car,
        ]);
    }
}

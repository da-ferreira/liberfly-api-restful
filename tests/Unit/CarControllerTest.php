<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Car;
use App\Models\User;

class CarControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    public function it_can_list_cars()
    {
        Car::factory()->count(15)->create();

        $response = $this->json('GET', '/api/auth/cars', ['perPage' => 10, 'currentPage' => 1]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'cars' => [
                'data' => [
                    '*' => ['id', 'brand', 'model', 'year', 'color', 'license_plate', 'created_at', 'updated_at']
                ],
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]
        ]);
    }

    public function it_can_create_a_car()
    {
        $data = [
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2021,
            'color' => 'Blue',
            'license_plate' => 'XYZ-1234',
        ];

        $response = $this->json('POST', '/api/auth/cars', $data);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Car created successfully',
            'car' => $data
        ]);

        $this->assertDatabaseHas('cars', $data);
    }

    public function it_can_show_a_car()
    {
        $car = Car::factory()->create();

        $response = $this->json('GET', "/api/auth/cars/{$car->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'car' => [
                'id' => $car->id,
                'brand' => $car->brand,
                'model' => $car->model,
                'year' => $car->year,
                'color' => $car->color,
                'license_plate' => $car->license_plate,
                'created_at' => $car->created_at->toISOString(),
                'updated_at' => $car->updated_at->toISOString(),
            ]
        ]);
    }

    public function it_returns_404_if_car_not_found()
    {
        $response = $this->json('GET', '/api/auth/cars/999');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Car not found'
        ]);
    }
}

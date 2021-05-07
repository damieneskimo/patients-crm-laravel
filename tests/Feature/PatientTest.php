<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->actingAs($this->admin);
    }

    public function test_can_create_patient()
    {
        $this->withoutExceptionHandling();
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'gender' => 'male'
        ];

        $response = $this->handleValidationExceptions()
            ->postJson(route('patients.store'), $data);

        $response->assertJsonMissingValidationErrors()
                ->assertCreated()
                ->assertJson($data);
    }

    public function test_can_get_patients_paginated_list()
    {
        $this->getJson('/api/patients')
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('meta')
                    ->has('links')
                    ->has('data', 15, function ($json) {
                        $json->whereAllType([
                            'id' => 'integer',
                            'name' => 'string',
                            'email' => 'string',
                            'mobile' => 'string'
                        ])
                            ->missing('password')
                            ->missing('is_admin')
                            ->etc();
                    });
            });
    }

    public function test_can_get_patients_filtered_by_keyword()
    {
        $patient = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com'
        ]);
        $this->getJson('/api/patients?keywords=john')
            ->assertOk()
            ->assertSee(['John Doe', 'john.doe@gmail.com']);
    }

    public function test_can_update_a_patient()
    {
        $patient = User::patients()->first();
        $this->putJson('/api/patients/' . $patient->id, [
                'name' => 'updated username',
                'mobile' => '12345'
            ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($patient) {
                $json->where('id', $patient->id)
                    ->where('name', 'updated username')
                    ->where('mobile', '12345')
                    ->missing('password')
                    ->etc();
            });
    }
}

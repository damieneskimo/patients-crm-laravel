<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_admin' => 1
        ]);
        $this->actingAs($this->admin);
    }

    public function test_validate_data_before_create_patient()
    {
        $data = [];
        $this->postJson(route('patients.store'), $data)
            ->assertStatus(422)
            ->assertJson(function (AssertableJson $json) use ($data) {
                $json->has('message')
                    ->has('errors', 3)
                    ->whereAllType([
                        'errors.name' => 'array',
                        'errors.email' => 'array',
                        'errors.gender' => 'array'
                    ]);
            });

        $data = [
            'name' => 'john doe',
            'email' => 'johndorandom',
        ];
        $this->postJson(route('patients.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(
                ['email', 'gender']
            )->assertJsonMissingValidationErrors('name');

        $data = [
            'name' => 'john doe',
            'email' => 'someunique@gmail.com',
            'gender' => 'something wrong'
        ];
        $this->postJson(route('patients.store'), $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors(
                ['gender']
            )->assertJsonMissingValidationErrors(['name', 'email']);
    }

    public function test_can_create_patient()
    {
        $this->withoutExceptionHandling();

        Storage::fake(storage_path('app/profiles'));
        $file = UploadedFile::fake()->image('profile.jpg', 300, 300);

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'gender' => 'male',
            'profile_photo' => $file,
        ];

        $response = $this->handleValidationExceptions()
            ->postJson(route('patients.store'), $data);

        //assert db record
        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ])->assertDatabaseCount('users', 2);

        Storage::disk('profiles')->assertExists($file->hashName());

        //replace file content with file url
        $data['profile_photo'] = Storage::disk('profiles')->url($file->hashName());

        $response->assertJsonMissingValidationErrors()
                ->assertCreated()
                ->assertJson($data);
    }

    public function test_can_show_a_patient()
    {
        $patient = User::factory()->create([
            'name' => 'test patient',
            'email' => 'test.patient@gmail.com'
        ]);
        $this->getJson('/api/patients/' . $patient->id)
            ->assertOk()
            ->assertJson([
                'name' => 'test patient',
                'email' => 'test.patient@gmail.com'
            ]);
    }

    public function test_can_get_patients_paginated_list()
    {
        $patients = User::factory(3)->create(['profile_photo' => null]);

        $this->getJson('/api/patients')
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('meta')
                    ->has('links')
                    ->has('data', 3, function ($json) {
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
        $patient = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com'
        ]);
        $this->putJson('/api/patients/' . $patient->id, [
                'name' => 'updated username',
                'mobile' => '666666'
            ])
            ->assertOk()
            ->assertJson(function (AssertableJson $json) use ($patient) {
                $json->where('id', $patient->id)
                    ->where('name', 'updated username')
                    ->where('mobile', '666666')
                    ->missing('password')
                    ->etc();
            });
    }
}

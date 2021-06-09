<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NoteTest extends TestCase
{
    protected $patient;

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

        $this->patient = User::create([
            'name' => 'patient',
            'email' => 'patient@gmail.com'
        ]);
    }

    public function test_cannot_create_if_validation_fails()
    {
        $data = ['content' => ''];
        $this->postJson('/api/patients/' . $this->patient->id . '/notes', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    public function test_can_create_note()
    {
        $this->postJson('/api/patients/' . $this->patient->id . '/notes', [
            'content' => 'lorem ipsum'
        ])
            ->assertCreated()
            ->assertJson([ 'content' => 'lorem ipsum' ]);
    }

    public function test_can_get_all_notes()
    {
        $notes = Note::factory(3)->create(['user_id' => $this->patient->id]);

        $this->getJson('/api/patients/' . $this->patient->id . '/notes')
            ->assertOk()
            ->assertJsonStructure([
                ['id', 'content', 'created_at']
            ])
            ->assertJsonCount(3);
    }
}

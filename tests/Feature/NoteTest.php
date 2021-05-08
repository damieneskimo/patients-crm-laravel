<?php

namespace Tests\Feature;

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

        $this->actingAs($this->admin);

        $this->patient = User::patients()->orderBy('id', 'desc')->first();
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
        $this->getJson('/api/patients/' . $this->patient->id . '/notes')
            ->assertOk()
            ->assertJsonStructure([
                'data', 'meta'
            ])
            ->assertJsonFragment([
                'meta' => [
                    'patient_name' => $this->patient->name
                ]
            ])
            ->assertJson(function (AssertableJson $json) {
                $json->has('meta', 1)
                    ->has('data.0', function(AssertableJson $item) {
                        $item->whereAllType([
                            'id' => 'integer',
                            'content' => 'string',
                            'created_at' => 'string'
                        ]);
                    });
            });
    }
}

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

        $this->actingAs($this->admin);

        $this->patient = User::patients()->orderBy('id', 'desc')->first();
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
        $this->getJson('/api/patients/' . $this->patient->id . '/notes')
            ->assertOk()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data.0', function(AssertableJson $item) {
                        $item->whereAllType([
                            'id' => 'integer',
                            'content' => 'string',
                            'created_at' => 'string'
                        ]);
                    });
            });
    }
}

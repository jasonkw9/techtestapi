<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SecretLab;
use App\Models\SecretLabChange;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testInsert()
    {
        $this->withoutExceptionHandling();
        $data = [
            'key' => $key = $this->faker->sentence,
            'value' => $value = $this->faker->paragraph,
        ];

        $this->post('/api/secretlab', $data)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Created successfully',
            ]);

            $this->assertDatabaseHas('secret_lab', [
                'key' => $key,
                'value' => $value
            ]);
    }

    public function testGetValueByKey() {
        $this->withoutExceptionHandling();

        $data = SecretLab::factory()->create();
        $this->get('/api/secretlab/'.$data->key)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Retrieved successfully',
                'data' => [
                    'key' => $data->key,
                    'value' => $data->value,
                ]
            ]);
    }

    public function testGetAllRecords() {
        $this->withoutExceptionHandling();

        $data = SecretLab::factory(5)->create();
        $this->get('/api/secretlab/get_all')
            ->assertStatus(200)
            ->assertJsonStructure([
                 'message', 'data', 'success' ,
            ])
            ->assertJsonCount(5, 'data');
    }

    public function testUpdateValueWithExistingKey() {
        $this->withoutExceptionHandling();

        $data = SecretLab::factory()->create();

        $newValue = [
            'key' => $data->key,
            'value' => $this->faker->paragraph
        ];

        $this->post('/api/secretlab/', $newValue)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Updated successfully',
            ]);
    }
  
    public function testGetValueByKeyAndTimestamp() {
        $this->withoutExceptionHandling();

        $data = SecretLab::factory()->create();

        $newValue = [
            'key' => $data->key,
            'value' => $value = $this->faker->paragraph
        ];
        $this->post('/api/secretlab/', $newValue)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Updated successfully',
            ]);

        $data2 = SecretLabChange::factory()->create([
            'key' => $data->key,
            'old_value' => $data->value,
            'updated_value' => $value,
            'original_created_at' => $data->created_at,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $currentTimestamp = strtotime("+1 minute");
        $this->get('/api/secretlab/'.$data->key.'?timestamp='.$currentTimestamp)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Retrieved successfully',
                'data' => $value,
            ]);

        $timestamp = strtotime($data->created_at);
        $this->get('/api/secretlab/'.$data->key.'?timestamp='.$timestamp)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Retrieved successfully',
                'data' => $data->value,
            ]);
    }
}

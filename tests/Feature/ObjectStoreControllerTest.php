<?php

namespace Tests\Feature;

use App\Models\ObjectStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectStoreControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the store method creates a new object store record successfully.
     */
    public function test_store_creates_object_store_successfully(): void
    {
        $payload = [
            'key' => 'test-key',
            'value' => ['foo' => 'bar', 'nested' => ['data' => 'value']],
        ];

        $response = $this->postJson('/api/object-store', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Resource created successfully',
                'data' => [],
            ]);

        $this->assertDatabaseHas('object_stores', [
            'key' => 'test-key',
            'value' => json_encode(['foo' => 'bar', 'nested' => ['data' => 'value']]),
        ]);
    }

    /**
     * Test that the store method saves the correct timestamp.
     */
    public function test_store_saves_timestamp_correctly(): void
    {
        $payload = [
            'key' => 'timestamp-test',
            'value' => ['test' => 'data'],
        ];

        $beforeTimestamp = now()->timestamp;

        $response = $this->postJson('/api/object-store', $payload);

        $afterTimestamp = now()->timestamp;

        $response->assertStatus(201);

        $objectStore = ObjectStore::where('key', 'timestamp-test')->first();

        $this->assertNotNull($objectStore);
        $this->assertGreaterThanOrEqual($beforeTimestamp, $objectStore->created_at_timestamp);
        $this->assertLessThanOrEqual($afterTimestamp, $objectStore->created_at_timestamp);
    }

    /**
     * Test that the store method handles simple string values.
     */
    public function test_store_handles_simple_values(): void
    {
        $payload = [
            'key' => 'simple-key',
            'value' => 'simple-string-value',
        ];

        $response = $this->postJson('/api/object-store', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('object_stores', [
            'key' => 'simple-key',
        ]);

        $objectStore = ObjectStore::where('key', 'simple-key')->first();
        $this->assertEquals('simple-string-value', $objectStore->value);
    }

    /**
     * Test that the store method handles complex nested arrays.
     */
    public function test_store_handles_complex_nested_arrays(): void
    {
        $complexValue = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'data' => 'deep-value',
                        'numbers' => [1, 2, 3, 4, 5],
                    ],
                ],
            ],
            'metadata' => [
                'created_by' => 'test-user',
                'tags' => ['important', 'test'],
            ],
        ];

        $payload = [
            'key' => 'complex-key',
            'value' => $complexValue,
        ];

        $response = $this->postJson('/api/object-store', $payload);

        $response->assertStatus(201);

        $objectStore = ObjectStore::where('key', 'complex-key')->first();
        $this->assertEquals($complexValue, $objectStore->value);
    }

    /**
     * Test that the store method handles empty values.
     */
    public function test_store_handles_empty_values(): void
    {
        $payload = [
            'key' => 'empty-key',
            'value' => [],
        ];

        $response = $this->postJson('/api/object-store', $payload);

        $response->assertStatus(201);

        $objectStore = ObjectStore::where('key', 'empty-key')->first();
        $this->assertEquals([], $objectStore->value);
    }

    /**
     * Test that multiple records can be created with different keys.
     */
    public function test_store_allows_multiple_records(): void
    {
        $payload1 = [
            'key' => 'key-1',
            'value' => ['data' => 'first'],
        ];

        $payload2 = [
            'key' => 'key-2',
            'value' => ['data' => 'second'],
        ];

        $this->postJson('/api/object-store', $payload1)->assertStatus(201);
        $this->postJson('/api/object-store', $payload2)->assertStatus(201);

        $this->assertDatabaseCount('object_stores', 2);
        $this->assertDatabaseHas('object_stores', ['key' => 'key-1']);
        $this->assertDatabaseHas('object_stores', ['key' => 'key-2']);
    }

    /**
     * Test that the store method handles boolean values.
     */
    public function test_store_handles_boolean_values(): void
    {
        $payload = [
            'key' => 'boolean-key',
            'value' => ['active' => true, 'disabled' => false],
        ];

        $response = $this->postJson('/api/object-store', $payload);

        $response->assertStatus(201);

        $objectStore = ObjectStore::where('key', 'boolean-key')->first();
        $this->assertTrue($objectStore->value['active']);
        $this->assertFalse($objectStore->value['disabled']);
    }

    /**
     * Test that the store method handles numeric values.
     */
    public function test_store_handles_numeric_values(): void
    {
        $payload = [
            'key' => 'numeric-key',
            'value' => ['integer' => 42, 'float' => 3.14, 'negative' => -100],
        ];

        $response = $this->postJson('/api/object-store', $payload);

        $response->assertStatus(201);

        $objectStore = ObjectStore::where('key', 'numeric-key')->first();
        $this->assertEquals(42, $objectStore->value['integer']);
        $this->assertEquals(3.14, $objectStore->value['float']);
        $this->assertEquals(-100, $objectStore->value['negative']);
    }
}

<?php

declare(strict_types=1);

use App\Models\CategoryModel;
use App\Models\Genre;
use Illuminate\Http\Response;

beforeEach(function () {
    $this->endpoint = '/api/genres';
});

test('list empty genres', function () {
    $response = $this->getJson($this->endpoint);
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonCount(0, 'data');
});

test('list all genres', function () {
    Genre::factory(30)->create();
    $response = $this->getJson($this->endpoint);

    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonStructure([
        'meta' => [
            'total',
            'current_page',
            'last_page',
            'first_page',
            'per_page',
            'to',
            'from'
        ]
    ]);
    $response->assertJsonCount(15, 'data');
});

test('list paginate genres', function () {
    Genre::factory(25)->create();
    $response = $this->getJson("{$this->endpoint}?page=2");
    $response->assertStatus(Response::HTTP_OK);
    $this->assertEquals(2, $response['meta']['current_page']);
    $this->assertEquals(25, $response['meta']['total']);
    $response->assertJsonCount(10, 'data');
});

test('list genre not found', function () {
    $response = $this->getJson("{$this->endpoint}/fake_value");
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('list genre', function () {
    $genre = Genre::factory()->create();
    $response = $this->getJson("{$this->endpoint}/{$genre->id}");
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonStructure(
        [
            'data' => [
                'id',
                'name',
                'is_active',
                'created_at',
            ]
        ]
    );
    $this->assertEquals($genre->id, $response['data']['id']);
});

test('validations store', function () {
    $data = [];
    $response = $this->postJson($this->endpoint, $data);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'name'
        ]
    ]);
});

test('test store', function () {
    $categories = CategoryModel::factory()->count(10)->create();
    $data = ['name' => 'new genre', 'categories_ids' => $categories->pluck('id')->toArray(),];
    $response = $this->postJson($this->endpoint, $data);
    $response->assertStatus(Response::HTTP_CREATED);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'is_active',
            'created_at',
        ]
    ]);
    $this->assertDatabaseHas('genres', [
        'name' => 'new genre',
    ]);
});

test('test not found genre update', function () {
    $categories = CategoryModel::factory(10)->create();
    $data = ['name' => 'new name not found', 'categories_ids' => $categories->pluck('id')->toArray()];
    $response = $this->putJson("{$this->endpoint}/id_fake", $data);
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('test validate genre update', function () {
    $genre = Genre::factory()->create();
    $data = [
        'name' => 'New Name to Update',
        'categories_ids' => []
    ];
    $response = $this->putJson("{$this->endpoint}/{$genre->id}", $data);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'categories_ids'
        ]
    ]);
});

test('test genre update', function () {
    $genre = Genre::factory()->create();
    $categories = CategoryModel::factory()->count(10)->create();
    $data = ['name' => 'name update', 'categories_ids' => $categories->pluck('id')->toArray()];
    $response = $this->putJson("{$this->endpoint}/{$genre->id}", $data);
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'is_active',
        ]
    ]);
    $this->assertDatabaseHas('genres', [
        'name' => 'name update',
    ]);
});

test('test delete not found genre', function () {
    $response = $this->deleteJson("{$this->endpoint}/invalid_id");
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('test delete genre', function () {
    $genre = Genre::factory()->create();
    $response = $this->deleteJson("{$this->endpoint}/{$genre->id}");
    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertSoftDeleted('genres', [
        'id' => $genre->id,
    ]);
});

<?php
declare(strict_types=1);

use Illuminate\Support\Str;
use App\Models\CategoryModel;
use Illuminate\Http\Response;
use Database\Factories\CategoryModelFactory;

beforeEach(function () {
    $this->endpoint = '/api/categories';
});

test('list empty categories', function () {
    $response = $this->getJson($this->endpoint);
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonCount(0, 'data');
});

test('list all categories', function () {
    CategoryModel::factory(30)->create();
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

test('list paginate categories', function () {
    CategoryModel::factory(25)->create();
    $response = $this->getJson("{$this->endpoint}?page=2");
    $response->assertStatus(Response::HTTP_OK);
    $this->assertEquals(2, $response['meta']['current_page']);
    $this->assertEquals(25, $response['meta']['total']);
    $response->assertJsonCount(10, 'data');
});

test('list category not found', function () {
    $response = $this->getJson("{$this->endpoint}/fake_value");
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('list category', function () {
    $category = CategoryModel::factory()->create();
    $response = $this->getJson("{$this->endpoint}/{$category->id}");
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonStructure(
        [
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
            ]
        ]
    );
    $this->assertEquals($category->id, $response['data']['id']);
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
    $data = ['name' => 'new category'];
    $response = $this->postJson($this->endpoint, $data);
    $response->assertStatus(Response::HTTP_CREATED);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'description',
            'is_active',
            'created_at',
        ]
    ]);
    $this->assertDatabaseHas('categories', [
        'name' => 'new category',
    ]);
});

test('test validate store description', function () {
    $data = ['name' => 'new category', 'description' => 'new descrription', 'is_active' => false];
    $response = $this->postJson($this->endpoint, $data);
    $response->assertStatus(Response::HTTP_CREATED);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'description',
            'is_active',
            'created_at',
        ]
    ]);
    $this->assertEquals('new category', $response['data']['name']);
    $this->assertEquals('new descrription', $response['data']['description']);
    $this->assertEquals(false, $response['data']['is_active']);
});

test('test not found category update', function () {
    $data = ['name' => 'new name'];
    $response = $this->putJson("{$this->endpoint}/id_fake", $data);
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('test validate category update', function () {
    $category = CategoryModel::factory()->create();
    $data = [];
    $response = $this->putJson("{$this->endpoint}/{$category->id}", $data);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'name'
        ]
    ]);
});

test('test category update', function () {
    $category = CategoryModel::factory()->create();
    $data = ['name' => 'name update'];
    $response = $this->putJson("{$this->endpoint}/{$category->id}", $data);
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'description',
            'is_active',
            'created_at',
        ]
    ]);
    $this->assertDatabaseHas('categories', [
        'name' => 'name update',
    ]);
});

test('test delete not found category', function () {
    $response = $this->deleteJson("{$this->endpoint}/invalid_id");
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

test('test delete category', function () {
    $category = CategoryModel::factory()->create();
    $response = $this->deleteJson("{$this->endpoint}/{$category->id}");
    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertSoftDeleted('categories', [
        'id' => $category->id,
    ]);
});

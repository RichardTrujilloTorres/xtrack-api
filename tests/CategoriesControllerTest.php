<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CategoriesControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function index()
    {
        /**
         * @var \App\Category[] $categories
         */
        $categories = factory(\App\Category::class, 10)->create();
        $response = $this->json('GET', '/api/categories');

        $response->assertResponseOk();

        $this->seeJsonContains([
            'name' => $categories[0]->name,
        ]);
    }

    /**
     * @test
     */
    public function show()
    {
        /**
         * @var \App\Category
         */
        $category = factory(\App\Category::class)->create();
        $response = $this->json('GET', '/api/categories/' . $category->slug);

        $response->assertResponseOk();

        $this->seeJsonContains([
            'name' => $category->name,
        ]);
    }

    /**
     * @test
     */
    public function returns404OnCategoryNotFound()
    {
        $response = $this->json('GET', '/api/categories/not-found');

        $response
            ->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function returns422OnInvalidRequest()
    {
        $response = $this->json('POST', '/api/categories');
        $response
            ->assertResponseStatus(422);

        $this->json('POST', '/api/categories', [
            'name' => 'dummy category',
            'slug' => '',
        ]);

        $response
            ->assertResponseStatus(201);

        $this->json('POST', '/api/categories', [
            'name' => 'dummy category',
        ]);
        $response
            ->assertResponseStatus(422);
    }

    /**
     * @test
     */
    public function itCreatesTheSlugWhenNotSpecified()
    {
        $this->json('POST', '/api/categories', [
            'name' => 'dummy category',
            'slug' => '',
        ]);

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'slug' => \Illuminate\Support\Str::slug('dummy category')
        ]);

        $this->json('POST', '/api/categories', [
            'name' => 'dummy category again',
        ]);

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'slug' => \Illuminate\Support\Str::slug('dummy category again')
        ]);
    }

    /**
     * @test
     */
    public function store()
    {
        $this->json('POST', '/api/categories', [
            'name' => 'dummy category',
            'description' => 'Dummy description.',
            'slug' => 'some slug',
        ]);

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'name' => 'dummy category',
            'description' => 'Dummy description.',
            'slug' => 'some slug',
        ]);
    }

    /**
     * @test
     */
    public function updateReturns404OnCategoryNotFound()
    {
        $response = $this->json('PUT', '/api/categories/not-found', [
            'name' => 'dummy name',
        ]);

        $response
            ->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function updateReturns422OnAlreadyExistingCategoryName()
    {
        /**
         * @var \App\Category $category
         */
        $category = factory(\App\Category::class)->create();
        $this->json('PUT', '/api/categories/' . $category->slug, [
            'name' => $category->name,
        ]);

        $this->assertResponseStatus(422);
    }

    /**
     * @test
     */
    public function update()
    {
        /**
         * @var \App\Category $category
         */
        $category = factory(\App\Category::class)->create();
        $this->json('PUT', '/api/categories/' . $category->slug, [
            'name' => 'updated',
        ]);

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'name' => 'updated',
            'description' => $category->description,
            'slug' => $category->slug,
        ]);
    }

    /**
     * @test
     */
    public function deleteReturn404OnNotFoundCategory()
    {
        $response = $this->json('GET', '/api/categories/not-found');

        $response
            ->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function deleteCategory()
    {
        /**
         * @var \App\Category $category
         */
        $category = factory(\App\Category::class)->create();
        $this->json('DELETE', '/api/categories/' . $category->slug);

        $this->assertResponseStatus(201);
        $this->seeJsonContains([
            'data' => [],
            'status' => 'success',
        ]);
    }
}

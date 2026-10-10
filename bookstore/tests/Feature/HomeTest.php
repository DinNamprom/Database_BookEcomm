<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully_with_featured_books(): void
    {
        $pub = Publisher::create(['name' => 'M / EDITIONS']);
        $author = Author::create(['first_name' => 'Rob', 'last_name' => 'Walker']);

        Book::create([
            'title' => 'The Art of Noticing',
            'genre' => 'ART & CULTURE',
            'price' => 24.00,
            'publisher_id' => $pub->id,
            'author_id' => $author->id,
            'in_stock' => 1,
            'format' => 'Paperback',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('The Art of Noticing');
        $response->assertSee('Rob Walker');
        $response->assertSee('ART & CULTURE');
        $response->assertSee('$24.00');
    }
}

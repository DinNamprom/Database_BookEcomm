<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Database\Seeders\BookSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowseBooksTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_browse_all_books_page_with_seeded_catalog(): void
    {
        $this->seed(BookSeeder::class);

        $response = $this->get(route('books.index'));

        $response->assertOk()
            ->assertSee('THE COLLECTION / PUBLIC CATALOG')
            ->assertSee('A shelf of possibilities.')
            ->assertSee('Browse freely. Please log in or create an account before adding books and placing an order.')
            ->assertSee('ALL BOOKS / 8 TITLES')
            ->assertSee('The Art of Noticing')
            ->assertSee('A Field Guide to Elsewhere')
            ->assertSee('The Quiet Between')
            ->assertSee('Ways of Seeing a City')
            ->assertSee('In Praise of the Ordinary')
            ->assertSee('A Wilder Kind of Life')
            ->assertSee('Letters from the Night')
            ->assertSee('On Going Slowly')
            ->assertSee('Showing 1–8 of 8 carefully chosen books');
    }

    public function test_authenticated_user_does_not_see_guest_login_banner(): void
    {
        $this->seed(BookSeeder::class);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('books.index'))
            ->assertOk()
            ->assertSee('A shelf of possibilities.')
            ->assertDontSee('Browse freely. Please log in or create an account before adding books and placing an order.');
    }

    public function test_can_filter_books_by_subject_format_availability_and_search(): void
    {
        $this->seed(BookSeeder::class);

        $publisher = Publisher::first();
        $author = Author::first();

        Book::create([
            'title' => 'Out of Print Archive',
            'genre' => 'ESSAYS',
            'price' => 40.00,
            'publisher_id' => $publisher->id,
            'author_id' => $author->id,
            'in_stock' => 0,
            'format' => 'Hardcover',
        ]);

        // Filter by subject: Essays (in stock only by default -> 2 titles)
        $this->get(route('books.index', ['subject' => 'essays']))
            ->assertOk()
            ->assertSee('ESSAYS / 2 TITLES')
            ->assertSee('In Praise of the Ordinary')
            ->assertSee('On Going Slowly')
            ->assertDontSee('The Art of Noticing')
            ->assertDontSee('Out of Print Archive');

        // Include out of stock books (in_stock = 0)
        $this->get(route('books.index', ['subject' => 'essays', 'in_stock' => '0']))
            ->assertOk()
            ->assertSee('ESSAYS / 3 TITLES')
            ->assertSee('Out of Print Archive');

        // Filter by format: Hardcover
        $this->get(route('books.index', ['format' => ['Hardcover']]))
            ->assertOk()
            ->assertSee('ALL BOOKS / 3 TITLES')
            ->assertSee('A Field Guide to Elsewhere')
            ->assertSee('In Praise of the Ordinary')
            ->assertSee('A Wilder Kind of Life')
            ->assertDontSee('The Art of Noticing');

        // Search by author or title
        $this->get(route('books.index', ['q' => 'Ada Finch']))
            ->assertOk()
            ->assertSee('ALL BOOKS / 1 TITLE')
            ->assertSee('Letters from the Night')
            ->assertDontSee('On Going Slowly');
    }

    public function test_can_sort_books_by_price_and_title(): void
    {
        $this->seed(BookSeeder::class);

        $this->get(route('books.index', ['sort' => 'price_asc']))
            ->assertOk()
            ->assertSeeInOrder([
                'Letters from the Night', // $18.00
                'On Going Slowly',        // $20.00
                'The Quiet Between',      // $22.00
                'The Art of Noticing',    // $24.00
                'In Praise of the Ordinary', // $26.00
                'Ways of Seeing a City',  // $28.00
                'A Wilder Kind of Life',  // $30.00
                'A Field Guide to Elsewhere', // $32.00
            ]);

        $this->get(route('books.index', ['sort' => 'price_desc']))
            ->assertOk()
            ->assertSeeInOrder([
                'A Field Guide to Elsewhere',
                'A Wilder Kind of Life',
                'Ways of Seeing a City',
                'In Praise of the Ordinary',
                'The Art of Noticing',
                'The Quiet Between',
                'On Going Slowly',
                'Letters from the Night',
            ]);
    }
}

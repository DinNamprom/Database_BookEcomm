<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;


class BookDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_a_book_detail_page(): void
    {
        $book = $this->createBook();

        // Ensure the related books section is rendered.
        $this->createBook();


        $this->get(route('books.show', ['book' => $book->slug]))
            ->assertOk()
            ->assertSee($book->title)
            ->assertSee('Keep following your curiosity.')
            ->assertSee('Log in to add to bag')
            ->assertSee(route('books.purchase.login', ['book' => $book->slug]));
    }

    public function test_guest_purchase_login_flow_returns_to_the_same_book_detail_page(): void
    {
        $book = $this->createBook();

        $this->get(route('books.purchase.login', ['book' => $book->slug]))
            ->assertRedirect(route('login'))
            ->assertSessionHas('url.intended', route('books.show', ['book' => $book->slug]));
    }

    public function test_guests_cannot_add_a_book_to_the_bag(): void
    {
        $book = $this->createBook();

        $this->post(route('cart.store', ['book' => $book->id]), [
            'quantity' => 1,
        ])->assertRedirect(route('login'));
    }

    public function test_authenticated_users_see_add_to_bag_without_login_prompts(): void
    {
        $book = $this->createBook();

        $this->actingAs(User::factory()->create())
            ->get(route('books.show', ['book' => $book->slug]))
            ->assertOk()
            ->assertSee('Add to bag')
            ->assertDontSee('Please log in or create an account before adding books')
            ->assertDontSee('Save book · Log in');
    }

    public function test_login_from_a_book_detail_page_redirects_back_to_that_book(): void
    {
        $book = $this->createBook();
        $user = User::factory()->create();

        $this->withSession([
            'url.intended' => route('books.show', ['book' => $book->slug]),
        ])->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('books.show', ['book' => $book->slug]));
    }

    private function createBook(): Book
    {
        $author = Author::create([
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
        ]);
        $publisher = Publisher::create(['name' => 'Margin Editions']);

        return Book::create([
            'title' => 'The Art of Noticing',
            'slug' => 'the-art-of-noticing'. Str::lower(Str::random(8)), 
            'genre' => 'Essays',
            'price' => 24.00,
            'description' => 'A book about noticing the world.',
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'in_stock' => 5,
            'publish_date' => '2026-01-01',
            'format' => 'Hardcover',
        ]);
    }
}

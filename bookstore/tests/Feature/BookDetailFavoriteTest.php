<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class BookDetailFavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_view_a_book_detail_page(): void
    {
        $book = $this->createBook();

        // Related Books section only appears when another book exists.
        $this->createBook();

        $this->get(route('books.show', ['book' => $book->slug]))
            ->assertOk()
            ->assertSee($book->title)
            ->assertSee('Keep following your curiosity.')
            ->assertSee('Log in to add to bag')
            ->assertSee(route('books.purchase.login', ['book' => $book->slug]));
    }

    public function test_member_can_save_and_remove_a_book_author_and_publisher_without_duplicates(): void
    {
        $user = User::factory()->create();
        $book = $this->createBook();

        $this->actingAs($user)->putJson(route('favorites.books.save', $book))
            ->assertOk()->assertExactJson(['saved' => true]);
        $this->putJson(route('favorites.books.save', $book))->assertOk();
       $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $this->assertDatabaseCount('wishlists', 1);

        $this->putJson(route('favorites.authors.save', $book->author))
            ->assertOk()->assertExactJson(['saved' => true]);
        $this->putJson(route('favorites.authors.save', $book->author))->assertOk();
        $this->assertDatabaseCount('favorite_authors', 1);

        $this->putJson(route('favorites.publishers.save', $book->publisher))
            ->assertOk()->assertExactJson(['saved' => true]);
        $this->putJson(route('favorites.publishers.save', $book->publisher))->assertOk();
        $this->assertDatabaseCount('favorite_publishers', 1);

        $this->deleteJson(route('favorites.books.remove', $book))->assertOk()->assertExactJson(['saved' => false]);
        $this->deleteJson(route('favorites.authors.remove', $book->author))->assertOk()->assertExactJson(['saved' => false]);
        $this->deleteJson(route('favorites.publishers.remove', $book->publisher))->assertOk()->assertExactJson(['saved' => false]);

        $this->assertDatabaseMissing('wishlists', ['user_id' => $user->id, 'book_id' => $book->id]);
        $this->assertDatabaseMissing('favorite_authors', ['user_id' => $user->id, 'author_id' => $book->author->id]);
        $this->assertDatabaseMissing('favorite_publishers', ['user_id' => $user->id, 'publisher_id' => $book->publisher->id]);
    }

    public function test_removing_favorites_does_not_affect_another_member(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $book = $this->createBook();

        $user->favoriteBooks()->attach($book);
        $user->favoriteAuthors()->attach($book->author);
        $user->favoritePublishers()->attach($book->publisher);
        $other->favoriteBooks()->attach($book);
        $other->favoriteAuthors()->attach($book->author);
        $other->favoritePublishers()->attach($book->publisher);

        $this->actingAs($user)->deleteJson(route('favorites.books.remove', $book))->assertOk();
        $this->deleteJson(route('favorites.authors.remove', $book->author))->assertOk();
        $this->deleteJson(route('favorites.publishers.remove', $book->publisher))->assertOk();

        $this->assertDatabaseHas('wishlists', ['user_id' => $other->id, 'book_id' => $book->id]);
        $this->assertDatabaseHas('favorite_authors', ['user_id' => $other->id, 'author_id' => $book->author->id]);
        $this->assertDatabaseHas('favorite_publishers', ['user_id' => $other->id, 'publisher_id' => $book->publisher->id]);
    }

    public function test_book_detail_renders_saved_states_for_the_current_member(): void
    {
        $user = User::factory()->create();
        $book = $this->createBook();
        $user->favoriteBooks()->attach($book);
        $user->favoriteAuthors()->attach($book->author);
        $user->favoritePublishers()->attach($book->publisher);

        $this->actingAs($user)->get(route('books.show', $book))
            ->assertOk()
            ->assertSee('data-favorite-name="Book"', false)
            ->assertSee('data-favorite-name="Author"', false)
            ->assertSee('data-favorite-name="Publisher"', false)
            ->assertSee('data-saved="true"', false)
            ->assertDontSee('Save book · Log in');
    }

    private function createBook(): Book
    {
        $author = Author::create(['first_name' => 'Toni', 'last_name' => 'Morrison']);
        $publisher = Publisher::create(['name' => 'Vintage']);

        return Book::create([
        'title' => 'Beloved',
        'slug' => 'beloved-' . Str::lower(Str::random(8)),
        'price' => 19.99,
        'author_id' => $author->id,
        'publisher_id' => $publisher->id,
        'in_stock' => 1,
        ]);
    }
}
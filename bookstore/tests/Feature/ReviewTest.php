<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_form_is_visible_only_to_signed_in_readers(): void
    {
        $book = $this->createBook();
        $url = route('books.show', ['book' => $book->slug]);

        $this->get($url)->assertOk()->assertDontSee('id="review-comment"', false);
        $this->actingAs(User::factory()->create())->get($url)
            ->assertOk()->assertSee('id="review-comment"', false)
            ->assertSee(route('reviews.store', ['book' => $book->slug]));
    }

    public function test_guests_cannot_submit_reviews(): void
    {
        $book = $this->createBook();

        $this->post(route('reviews.store', ['book' => $book->slug]), [
            'rating_star' => 5,
            'comment' => 'A thoughtful book.',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_reviews_use_the_signed_in_reader_and_book_from_the_url(): void
    {
        $book = $this->createBook();
        $otherBook = $this->createBook();
        $reader = User::factory()->create();
        $otherReader = User::factory()->create();
        $url = route('books.show', ['book' => $book->slug]);

        $this->actingAs($reader)
            ->post(route('reviews.store', ['book' => $book->slug]), [
                'rating_star' => 4,
                'comment' => '<script>alert("test")</script>',
                'user_id' => $otherReader->id,
                'book_id' => $otherBook->id,
            ])->assertRedirect($url.'#book-reviews')
            ->assertSessionHas('review_success');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $reader->id,
            'book_id' => $book->id,
            'rating_star' => 4,
            'comment' => '<script>alert("test")</script>',
        ]);
        $this->assertDatabaseCount('reviews', 1);

        $this->get($url)->assertOk()->assertSee('4.0 / 5')
            ->assertSee('<script>alert("test")</script>')
            ->assertDontSee('<script>alert("test")</script>', false);
        $this->get(route('books.show', ['book' => $otherBook->slug]))
            ->assertOk()->assertDontSee('alert(&quot;test&quot;)', false);
    }

    public function test_invalid_ratings_and_overlong_comments_are_not_saved(): void
    {
        $book = $this->createBook();
        $url = route('reviews.store', ['book' => $book->slug]);
        $this->actingAs(User::factory()->create());

        foreach ([null, 0, 6, 2.5, 'invalid'] as $rating) {
            $this->post($url, ['rating_star' => $rating])
                ->assertSessionHasErrors('rating_star');
        }
        $this->post($url, ['rating_star' => 5, 'comment' => str_repeat('x', 5001)])
            ->assertSessionHasErrors('comment');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_a_rating_can_be_submitted_without_a_comment(): void
    {
        $book = $this->createBook();
        $reader = User::factory()->create();

        $this->actingAs($reader)->post(route('reviews.store', ['book' => $book->slug]), [
            'rating_star' => 5,
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'user_id' => $reader->id,
            'book_id' => $book->id,
            'rating_star' => 5,
            'comment' => null,
        ]);
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

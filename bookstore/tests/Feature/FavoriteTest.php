<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_read_or_remove_favorites(): void
    {
        $this->getJson(route('favorites.data'))->assertUnauthorized();
        $this->deleteJson(route('favorites.authors.remove', 1))->assertUnauthorized();
        $this->deleteJson(route('favorites.publishers.remove', 1))->assertUnauthorized();
    }

    public function test_member_with_no_favorites_gets_empty_sections(): void
    {
        $this->actingAs(User::factory()->create())
            ->getJson(route('favorites.data'))
            ->assertOk()->assertExactJson([
                'authors_count' => 0, 'publishers_count' => 0,
                'authors' => [], 'publishers' => [],
            ]);
    }

    public function test_favorites_include_only_current_member_and_related_books(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $author = Author::create(['first_name' => 'Own', 'last_name' => 'Author']);
        $otherAuthor = Author::create(['first_name' => 'Other', 'last_name' => 'Author']);
        $publisher = Publisher::create(['name' => 'Saved Publisher']);
        $otherPublisher = Publisher::create(['name' => 'Other Publisher']);
        $book = DB::table('books')->insertGetId([
            'title' => 'Related book', 'price' => 250, 'format' => 'Paperback',
            'author_id' => $author->id, 'publisher_id' => $publisher->id,
        ]);
        $user->favoriteAuthors()->attach($author);
        $user->favoritePublishers()->attach($publisher);
        $other->favoriteAuthors()->attach($otherAuthor);
        $other->favoritePublishers()->attach($otherPublisher);

        $this->actingAs($user)->getJson(route('favorites.data'))
            ->assertOk()->assertJsonPath('authors_count', 1)
            ->assertJsonPath('publishers_count', 1)
            ->assertJsonPath('authors.0.name', 'Own Author')
            ->assertJsonPath('authors.0.books.0.id', $book)
            ->assertJsonPath('publishers.0.books_count', 1)
            ->assertJsonPath('publishers.0.books.0.author.first_name', 'Own')
            ->assertJsonMissing(['name' => 'Other Author'])
            ->assertJsonMissing(['name' => 'Other Publisher']);
    }

    public function test_removing_an_author_preserves_other_members_favorites_and_author(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $author = Author::create(['first_name' => 'Shared', 'last_name' => 'Author']);
        $user->favoriteAuthors()->attach($author);
        $other->favoriteAuthors()->attach($author);

        $this->actingAs($user)->deleteJson(route('favorites.authors.remove', $author->id))->assertOk()->assertExactJson(['saved' => false]);
        $this->assertDatabaseMissing('favorite_authors', ['user_id' => $user->id, 'author_id' => $author->id]);
        $this->assertDatabaseHas('favorite_authors', ['user_id' => $other->id, 'author_id' => $author->id]);
        $this->assertDatabaseHas('authors', ['id' => $author->id]);
        $this->getJson(route('favorites.data'))->assertJsonPath('authors_count', 0);
        $this->deleteJson(route('favorites.authors.remove', $author->id))->assertOk()->assertExactJson(['saved' => false]);
    }

    public function test_removing_a_publisher_does_not_remove_another_members_relationship(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $publisher = Publisher::create(['name' => 'Shared Publisher']);
        $other->favoritePublishers()->attach($publisher);

        $this->actingAs($user)->deleteJson(route('favorites.publishers.remove', $publisher->id))->assertOk()->assertExactJson(['saved' => false]);
        $this->assertDatabaseHas('favorite_publishers', ['user_id' => $other->id, 'publisher_id' => $publisher->id]);
        $user->favoritePublishers()->attach($publisher);
        $this->deleteJson(route('favorites.publishers.remove', $publisher->id))->assertOk()->assertExactJson(['saved' => false]);
        $this->assertDatabaseMissing('favorite_publishers', ['user_id' => $user->id, 'publisher_id' => $publisher->id]);
        $this->assertDatabaseHas('publishers', ['id' => $publisher->id]);
    }
}

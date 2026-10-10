<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MemberPagesViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_both_pages_require_login(): void
    {
        $this->get(route('favorites.index'))->assertRedirect(route('login'));
        $this->get(route('member-information.index'))->assertRedirect(route('login'));
    }

    public function test_member_page_displays_only_current_members_existing_fields(): void
    {
        $user = User::factory()->create([
            'name' => 'Current Member', 'first_name' => 'Current', 'last_name' => 'Member',
            'email' => 'current@example.com', 'address' => "12 Reading Road\nBangkok",
        ]);
        User::factory()->create(['email' => 'private@example.com', 'address' => 'Private address']);

        $this->actingAs($user)->get(route('member-information.index'))
            ->assertOk()->assertSee('Personal details')->assertSee('Current Member')
            ->assertSee('current@example.com')->assertSee('12 Reading Road')
            ->assertSee(route('member-information.update'))
            ->assertSee(route('member-information.address.update'))
            ->assertDontSee('private@example.com')->assertDontSee('Private address')
            ->assertDontSee('PHONE NUMBER')->assertDontSee('Add another address');
    }

    public function test_favorite_page_renders_saved_books_and_escapes_member_content(): void
    {
        $user = User::factory()->create();
        $author = Author::create(['first_name' => '<script>alert(1)</script>', 'last_name' => 'Writer']);
        $publisher = Publisher::create(['name' => 'Saved press']);
        DB::table('books')->insert([
            'title' => 'A saved book', 'price' => 250, 'format' => 'Paperback',
            'author_id' => $author->id, 'publisher_id' => $publisher->id,
        ]);
        $user->favoriteAuthors()->attach($author);
        $user->favoritePublishers()->attach($publisher);
        $this->actingAs($user)->get(route('favorites.index'))
            ->assertOk()->assertSee('Fav Authors')->assertSee('Fav Publishers')
            ->assertSee('A saved book')->assertSee('฿250.00')->assertSee('Saved press')
            ->assertSee('<script>alert(1)</script>')->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee(route('favorites.authors.remove', $author->id))
            ->assertSee(route('favorites.publishers.remove', $publisher->id));
    }

    public function test_favorite_page_handles_empty_library(): void
    {
        $this->actingAs(User::factory()->create())->get(route('favorites.index'))
            ->assertOk()->assertSee('No favorite authors saved yet.')
            ->assertSee('No favorite publishers saved yet.');
    }
}

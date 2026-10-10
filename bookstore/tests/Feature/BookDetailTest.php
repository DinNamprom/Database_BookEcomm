<?php

namespace Tests\Feature;

use Tests\TestCase;

class BookDetailTest extends TestCase
{
    public function test_the_static_book_detail_page_can_be_rendered(): void
    {
        $this->get(route('books.the-art-of-noticing'))
            ->assertOk()
            ->assertSee('The Art of Noticing')
            ->assertSee('Keep following your curiosity.')
            ->assertSee('New books, thoughtful notes.');
    }
}

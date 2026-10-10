<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $pub1 = Publisher::create(['name' => 'M / EDITIONS']);

        $author1 = Author::create(['first_name' => 'Rob', 'last_name' => 'Walker']);
        $author2 = Author::create(['first_name' => 'Maya', 'last_name' => 'Ellison']);
        $author3 = Author::create(['first_name' => 'Clara', 'last_name' => 'Reed']);
        $author4 = Author::create(['first_name' => 'Jonas', 'last_name' => 'Bell']);

        Book::create([
            'title' => 'The Art of Noticing',
            'genre' => 'ART & CULTURE',
            'price' => 24.00,
            'publisher_id' => $pub1->id,
            'author_id' => $author1->id,
            'in_stock' => 1,
            'format' => 'Paperback',
        ]);

        Book::create([
            'title' => 'A Field Guide to Elsewhere',
            'genre' => 'TRAVEL & PLACE',
            'price' => 32.00,
            'publisher_id' => $pub1->id,
            'author_id' => $author2->id,
            'in_stock' => 1,
            'format' => 'Hardcover',
        ]);

        Book::create([
            'title' => 'The Quiet Between',
            'genre' => 'FICTION',
            'price' => 22.00,
            'publisher_id' => $pub1->id,
            'author_id' => $author3->id,
            'in_stock' => 1,
            'format' => 'Paperback',
        ]);

        Book::create([
            'title' => 'Ways of Seeing a City',
            'genre' => 'ART & CULTURE',
            'price' => 28.00,
            'publisher_id' => $pub1->id,
            'author_id' => $author4->id,
            'in_stock' => 1,
            'format' => 'Paperback',
        ]);
    }
}

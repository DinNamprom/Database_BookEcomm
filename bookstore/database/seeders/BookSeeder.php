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
        $publisher = Publisher::firstOrCreate(['name' => 'M / EDITIONS']);

        $catalog = [
            [
                'title' => 'The Art of Noticing',
                'slug' => 'the-art-of-noticing',
                'genre' => 'ART & CULTURE',
                'price' => 24.00,
                'format' => 'Paperback',
                'in_stock' => 1,
                'publish_date' => '2026-10-08',
                'description' => 'An quietly radical field guide to paying attention, rediscovering everyday wonder, and noticing what everyone else overlooks.',
                'author' => ['first_name' => 'Rob', 'last_name' => 'Walker'],
            ],
            [
                'title' => 'A Field Guide to Elsewhere',
                'slug' => 'a-field-guide-to-elsewhere',
                'genre' => 'TRAVEL & PLACE',
                'price' => 32.00,
                'format' => 'Hardcover',
                'in_stock' => 1,
                'publish_date' => '2026-10-07',
                'description' => 'Notes from unfamiliar landscapes, remote stations, and the quiet geography of longing.',
                'author' => ['first_name' => 'Maya', 'last_name' => 'Ellison'],
            ],
            [
                'title' => 'The Quiet Between',
                'slug' => 'the-quiet-between',
                'genre' => 'FICTION',
                'price' => 22.00,
                'format' => 'Paperback',
                'in_stock' => 1,
                'publish_date' => '2026-10-06',
                'description' => 'A spare, luminous novel about two strangers, a coastal winter, and the conversations that happen in silence.',
                'author' => ['first_name' => 'Clara', 'last_name' => 'Reed'],
            ],
            [
                'title' => 'Ways of Seeing a City',
                'slug' => 'ways-of-seeing-a-city',
                'genre' => 'ART & CULTURE',
                'price' => 28.00,
                'format' => 'Paperback',
                'in_stock' => 1,
                'publish_date' => '2026-10-05',
                'description' => 'Architectural essays on sidewalks, storefronts, transit lines, and the unspoken rhythms of urban life.',
                'author' => ['first_name' => 'Jonas', 'last_name' => 'Bell'],
            ],
            [
                'title' => 'In Praise of the Ordinary',
                'slug' => 'in-praise-of-the-ordinary',
                'genre' => 'ESSAYS',
                'price' => 26.00,
                'format' => 'Hardcover',
                'in_stock' => 1,
                'publish_date' => '2026-10-04',
                'description' => 'Meditations on morning rituals, worn tables, handwritten lists, and the quiet dignity of daily life.',
                'author' => ['first_name' => 'Eva', 'last_name' => 'March'],
            ],
            [
                'title' => 'A Wilder Kind of Life',
                'slug' => 'a-wilder-kind-of-life',
                'genre' => 'NATURE',
                'price' => 30.00,
                'format' => 'Hardcover',
                'in_stock' => 1,
                'publish_date' => '2026-10-03',
                'description' => 'A season-by-season chronicle of living close to weather, watershed, and untamed ground.',
                'author' => ['first_name' => 'Theo', 'last_name' => 'Moss'],
            ],
            [
                'title' => 'Letters from the Night',
                'slug' => 'letters-from-the-night',
                'genre' => 'POETRY',
                'price' => 18.00,
                'format' => 'Paperback',
                'in_stock' => 1,
                'publish_date' => '2026-10-02',
                'description' => 'Intimate nocturnal poems written between midnight and first light.',
                'author' => ['first_name' => 'Ada', 'last_name' => 'Finch'],
            ],
            [
                'title' => 'On Going Slowly',
                'slug' => 'on-going-slowly',
                'genre' => 'ESSAYS',
                'price' => 20.00,
                'format' => 'Paperback',
                'in_stock' => 1,
                'publish_date' => '2026-10-01',
                'description' => 'A small philosophy of unhurried hours, long walks, and resisting the tyranny of urgency.',
                'author' => ['first_name' => 'Leon', 'last_name' => 'Hayes'],
            ],
        ];

        foreach ($catalog as $entry) {
            $author = Author::firstOrCreate($entry['author']);

            Book::updateOrCreate(
                ['slug' => $entry['slug']],
                [
                    'title' => $entry['title'],
                    'genre' => $entry['genre'],
                    'price' => $entry['price'],
                    'description' => $entry['description'],
                    'publisher_id' => $publisher->id,
                    'author_id' => $author->id,
                    'in_stock' => $entry['in_stock'],
                    'publish_date' => $entry['publish_date'],
                    'format' => $entry['format'],
                ]
            );
        }
    }
}

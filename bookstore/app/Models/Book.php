<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Book extends Model
{
    /** @return BelongsTo<Author, $this> */
    public $timestamps = false; // Existing migration didn't have timestamps

    protected $fillable = [
        'title',
        'slug',
        'genre',
        'price',
        'description',
        'publisher_id',
        'author_id',
        'in_stock',
        'publish_date',
        'format',
        'cover_image',
        'cover_bg_color',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'in_stock' => 'boolean',
        'publish_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Book $book) {
            if (filled($book->slug)) {
                return;
            }

            $baseSlug = Str::slug($book->title) ?: 'book';
            $slug = $baseSlug;
            $counter = 2;

            while (static::query()->where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            $book->slug = $slug;
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /** @return BelongsTo<Publisher, $this> */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

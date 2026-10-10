<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
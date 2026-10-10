<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        $books = DB::table('books')
            ->select('id', 'title')
            ->orderBy('id')
            ->get();

        foreach ($books as $book) {
            $baseSlug = Str::slug($book->title);

            if ($baseSlug === '') {
                $baseSlug = 'book';
            }

            $slug = $baseSlug;
            $counter = 2;

            while (
                DB::table('books')
                    ->where('slug', $slug)
                    ->where('id', '!=', $book->id)
                    ->exists()
            ) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            DB::table('books')
                ->where('id', $book->id)
                ->update([
                    'slug' => $slug,
                ]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
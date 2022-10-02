<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Library;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $books = Book::factory(50)->create();
        Library::factory(10)->create()->each(function ($lib) use ($books) {
            $lib->books()->attach($books->random());
        });
    }
}

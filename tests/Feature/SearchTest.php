<?php

namespace Tests\Feature;

use App\Models\Book;
use Tests\TestCase;

class SearchTest extends TestCase
{

    public function testSearch()
    {
        $book = Book::query()->first();

        $this->json('GET', "api/search/$book->title")
            ->assertStatus(200)
            ->assertJsonCount(1);
    }
}

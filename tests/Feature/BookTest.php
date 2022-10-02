<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Library;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BookTest extends TestCase
{

    const URI = 'api/book';

    public function setUp(): void
    {
        parent::setUp();
        $email = Config::get('api.apiEmail');
        $password = Config::get('api.apiPassword');
        $this->json('POST', 'api/auth/login', [
            'email' => $email,
            'password' => $password
        ]);
    }

    public function testStore()
    {
        $lib = Library::factory(3)->create();
        $lib_ids = $lib->map(function ($item) {
            $res['id'] = $item->id;
            return $res;
        });

        $response = $this->json('POST', self::URI, [
            'title' => 'test title',
            'description' => 'test description',
            'author' => 'test author',
            'stock' => 10,
            'libs' => $lib_ids,
        ])->assertStatus(200);

        foreach ($lib as $item) {
            $this->assertDatabaseHas('library_books', [
                'book_id' => $response['id'],
                'library_id' => $item->id,
                'stock' => 10
            ]);
        }

        $this->assertDatabaseHas('books', [
            'id' => $response['id'],
            'title' => 'test title',
            'description' => 'test description',
            'author' => 'test author',
        ]);
    }

    public function testUpdate()
    {
        $book = Book::factory()->create();
        $lib = Library::factory()->create();

        $this->json('PUT', self::URI . "/$book->id", [
            'title' => 'test',
            'description' => 'description',
            'author' => 'author',
            'stock' => 10,
            'libs' => [$lib->id],
        ])->assertStatus(200);
    }

    public function testDestroy()
    {
        $book = Book::factory()->create();
        $lib = Library::factory()->create();

        $this->json('DELETE', self::URI . "/$book->id/$lib->id", [
            'id' => $book->id,
            'lib_id' => $lib->id,
        ])->assertStatus(200);
    }

    public function testLike()
    {
        $book = Book::factory()->create();

        //set like
        $this->json('POST', self::URI . "/like", [
            'id' => $book->id,
        ])->assertStatus(200);

        $this->assertDatabaseHas('book_user_likes', [
            'book_id' => $book->id,
            'user_id' => auth()->user()->id,
        ]);

        //unset like
        $this->json('POST', self::URI . "/like", [
            'id' => $book->id,
        ])->assertStatus(200);

        $this->assertDatabaseMissing('book_user_likes', [
            'book_id' => $book->id,
            'user_id' => auth()->user()->id,
        ]);
    }

    public function testGetLikes()
    {
        $this->json('GET', self::URI . "/likes")->assertStatus(200);
    }

    public function testGetAll()
    {
        $this->json('GET', self::URI . "/all")->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Library;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class LibraryTest extends TestCase
{
    const URI = 'api/library';

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
        $this->json('POST', self::URI, [
            'name' => 'Test Library',
        ])->assertStatus(200);

        $this->assertDatabaseHas('libraries', [
            'name' => 'Test Library',
        ]);
    }

    public function testIndex()
    {
        $this->json('GET', self::URI)->assertStatus(200);
    }

    public function testDestroy()
    {
        $book = Book::factory()->create();
        $lib = Library::factory()->create();
        $lib->books()->attach($book->id);

        $this->json('DELETE', self::URI . "/$lib->id", [
            'id' => $lib->id,
        ])->assertStatus(200);

        $this->assertDatabaseMissing('libraries', [
            'id' => $book->id,
            'name' => $book->name,
        ]);

        $this->assertDatabaseMissing('library_books', [
            'book_id' => $book->id,
            'library_id' => $lib->id,
        ]);
    }

    public function testShow()
    {
        $lib = Library::factory()->create();

        $this->json('GET', self::URI, [
            'id' => $lib->id,
        ])->assertStatus(200);
    }

    public function testDetach()
    {
        $book = Book::factory()->create();
        $lib = Library::factory()->create();
        $lib->books()->attach($book->id);

        $this->json('POST', self::URI . "/detach/$book->id/$lib->id")
            ->assertStatus(200);

        $this->assertDatabaseMissing('library_books', [
            'book_id' => $book->id,
            'library_id' => $lib->id,
        ]);
    }

    public function testAttach()
    {
        $book = Book::factory(10)->create();
        $lib = Library::factory()->create();

        $dataTest = [];
        foreach ($book as $item) {
            $dataTest[$item['id']] = ['stock' => 1, 'id' => $item['id']];
        }

        $this->json('PUT', self::URI . "/attach/$lib->id", $dataTest)
            ->assertStatus(200);

        foreach ($dataTest as $id => $item) {
            $this->assertDatabaseHas('library_books', [
                'book_id' => $id,
                'library_id' => $lib->id,
                'stock' => 1,
            ]);
        }
    }

    public function testUpdate()
    {
        $lib = Library::factory()->create();

        $this->json('PUT', self::URI . "/update/$lib->id", ['name' => 'Updated name'])
            ->assertStatus(200);

        $this->assertDatabaseHas('libraries', [
            'id' => $lib->id,
            'name' => 'Updated name',
        ]);
    }
}

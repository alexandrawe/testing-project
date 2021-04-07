<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_added_to_the_library()
    {
        $response = $this->post('/books', [
            'title' => 'cool book title',
            'author' => 'alex'
        ]);

        
        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect($book->path());
    }

    public function test_a_title_is_required()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'alex'
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_an_author_is_required()
    {
        $response = $this->post('/books', [
            'title' => 'my cool book',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    public function test_a_book_can_be_updated()
    {
        $this->post('/books', [
            'title' => 'my cool book',
            'author' => 'alex'
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'my new cool book title',
            'author' => 'new alex',
        ]);

        $this->assertEquals('my new cool book title', Book::first()->title);
        $this->assertEquals('new alex', Book::first()->author);

        $response->assertRedirect($book->fresh()->path());
    }

    public function test_a_book_can_be_deleted()
    {
        $this->post('/books', [
            'title' => 'my cool book',
            'author' => 'alex'
        ]);

        $book = Book::first();
        $this->assertEquals(1, Book::count());

        $response = $this->delete($book->path());

        $this->assertEquals(0, Book::count());
        $response->assertRedirect('/books');
    }
}

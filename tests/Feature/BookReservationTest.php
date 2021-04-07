<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();
        
        $response = $this->post('/books', [
            'title' => 'cool book title',
            'author' => 'alex'
        ]);

        $response->assertOk();
        $this->assertCount(1, Book::all());
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
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'my cool book',
            'author' => 'alex'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/'.$book->id, [
            'title' => 'my new cool book title',
            'author' => 'new alex',
        ]);

        $this->assertEquals('my new cool book title', Book::first()->title);
        $this->assertEquals('new alex', Book::first()->author);
    }
}

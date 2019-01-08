<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class StoreThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->actingAs($this->user);
    }

    /** @test */
    public function users_can_store_threads()
    {
        $this->assertEquals(0, Thread::count());

        $data = [
            'title' => 'Some Thread',
            'content' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry' s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
        ];

        $this->postJson(route('threads.store'), $data)
            ->assertStatus(201);

        $this->assertEquals(1, Thread::count());

        $thread = Thread::first();

        $this->assertEquals($this->user->id, $thread['created_by']);
        $this->assertEquals($data['title'], $thread['title']);
        $this->assertEquals($data['content'], $thread['content']);
    }

    /** @test */
    public function title_is_required()
    {
        $this->withExceptionHandling();

        $this->postJson(route('threads.store'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function title_must_be_at_least_3_chars_long()
    {
        $this->withExceptionHandling();

        $this->postJson(route('threads.store'), ['title' => 'qw'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function title_must_be_unique()
    {
        $this->withExceptionHandling();
        
        $thread = factory(Thread::class)->create(['title' => 'The Title']);

        $this->postJson(route('threads.store'), ['title' => $thread->title])
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function title_must_not_contain_numbers()
    {
        $this->withExceptionHandling();

        $this->postJson(route('threads.store'), ['title' => 'qwer1y'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function content_is_optional()
    {
        $this->postJson(route('threads.store'), ['title' => 'qwerty'])
            ->assertStatus(201);
    }

    /** @test */
    public function content_cannot_be_longer_than_255_chars()
    {
        $this->withExceptionHandling();

        $this->postJson(route('threads.store'), [
            'content' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry' s standard dummy text ever since the 1500 s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('content');
    }

    /** @test */
    public function content_must_end_with_a_dot()
    {
        $this->withExceptionHandling();

        $this->postJson(route('threads.store'), ['content' => 'At the end. No dot'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    /** @test */
    public function a_user_cannot_have_more_than_5_threads()
    {
        // Our user already has 5 threads
        $threads = factory(Thread::class, 5)->create([
            'created_by' => $this->user->id,
        ]);

        $this->assertCount(5, $this->user->threads);

        // Let's create a new thread
        $data = [
            'title' => 'Some Thread',
            'content' => 'Content.',
        ];

        $this->postJson(route('threads.store'), $data)
            ->assertStatus(201);

        // The number of threads should still be 5
        $this->assertCount(5, $this->user->fresh()->threads);

        // The very first (original) thread should've been deleted
        $this->assertDatabaseMissing('threads', ['id' => 1]);
    }
}

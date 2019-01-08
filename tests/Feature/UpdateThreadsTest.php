<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_update_own_threads()
    {
        $thread = factory(Thread::class)->create([
            'created_by' => $this->user->id,
        ]);

        $data = [
            'title' => 'Edited title',
            'content' => 'Edited content.',
        ];

        $this->actingAs($this->user)
            ->putJson(route('threads.update', $thread), $data)
            ->assertStatus(200);

        $thread = $thread->fresh();

        $this->assertEquals($data['title'], $thread['title']);
        $this->assertEquals($data['content'], $thread['content']);
    }

    /** @test */
    public function users_cant_update_threads_they_dont_own()
    {
        $this->withExceptionHandling();

        // By default the thread will be created by some newly generated user
        $thread = factory(Thread::class)->create();

        $this->actingAs($this->user)
            ->putJson(route('threads.update', $thread), [])
            ->assertStatus(403);
    }

    /** @test */
    public function not_changing_the_title_doesnt_produce_an_error()
    {
        $thread = factory(Thread::class)->create([
            'created_by' => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->putJson(route('threads.update', $thread), $thread->toArray())
            ->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class ViewThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_threads()
    {
        $thread = factory(Thread::class)->create();

        $this->actingAs($this->user)
            ->get(route('threads.show', $thread))
            ->assertStatus(200)
            ->assertSeeText($thread->title)
            ->assertSeeText($thread->content);
    }
}

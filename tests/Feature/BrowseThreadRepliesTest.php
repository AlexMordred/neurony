<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;

class BrowseThreadRepliesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_browse_thread_replies()
    {
        // Create a thread
        $thread = factory(Thread::class)->create();

        // Create some replies for the thread
        factory(Reply::class, $total = 50)->create([
            'thread_id' => $thread->id,
        ]);

        // Get the paginated list of replies
        $replies = $this->actingAs($this->user)
            ->getJson(route('threads.replies', $thread))
            ->assertStatus(200)
            ->json();

        $this->assertEquals($total, $replies['total']);
        $this->assertCount(5, $replies['data']);
    }
}

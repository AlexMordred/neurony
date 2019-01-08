<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;
use Illuminate\Support\Facades\Event;
use App\Events\NewReply;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReplyMail;

class StoreThreadRepliesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_leave_new_replies_in_threads()
    {
        Event::fake();

        // We have a thread
        $thread = factory(Thread::class)->create();
        // With 0 collaborators
        $this->assertCount(0, $thread->collaborators);

        // Which should have 0 replies
        $this->assertCount(0, $thread->replies);

        $data = [
            'content' => 'Text of the reply.'
        ];

        // A logged in user can leave a reply in this thread
        $this->actingAs($this->user)
            ->postJson(route('threads.replies.store', $thread), $data)
            ->assertStatus(201);

        $thread = $thread->fresh();

        // Now we should have exactly 1 reply
        $this->assertCount(1, $thread->replies);

        $reply = Reply::first();

        $this->assertEquals($data['content'], $reply['content']);

        // Make sure the user was registered as a collaborator for the thread
        $this->assertCount(1, $thread->collaborators);

        // Make sure the thread's author was notified via email
        // Event::assertDispatched(NewReply::class, function ($e) use ($reply) {
        //     return $e->reply->id == $reply->id;
        // });

        
    }

    /** @test */
    public function an_email_is_sent_to_the_thread_author_on_a_new_reply()
    {
        Mail::fake();

        $thread = factory(Thread::class)->create();

        $data = [
            'content' => 'Text of the reply.'
        ];

        $this->actingAs($this->user)
            ->postJson(route('threads.replies.store', $thread), $data)
            ->assertStatus(201);

        $reply = Reply::first();

        // Make sure an email was sent
        Mail::assertSent(NewReplyMail::class, function ($mail) use ($reply) {
            return $mail->reply->id == $reply->id;
        });
    }
}

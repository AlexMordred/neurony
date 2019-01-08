<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class DestroyThreadTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function users_can_destroy_own_tests()
    {
        $thread = factory(Thread::class)->create([
            'created_by' => $this->user->id,
        ]);

        $this->assertEquals(1, Thread::count());

        $this->actingAs($this->user)
            ->deleteJson(route('threads.destroy', $thread))
            ->assertStatus(200);

        $this->assertEquals(0, Thread::count());
    }

    /** @test */
    public function users_cant_destroy_tests_they_dont_own()
    {
        $this->withExceptionHandling();

        // By default the thread will be created by some newly generated user
        $thread = factory(Thread::class)->create();

        $this->actingAs($this->user)
            ->deleteJson(route('threads.destroy', $thread))
            ->assertStatus(403);
    }
}

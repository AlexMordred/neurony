<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class BrowseOwnThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_see_own_thread_on_the_profile_page()
    {
        $ownThreads = factory(Thread::class, 5)->create([
            'created_by' => $this->user->id,
        ]);

        $otherThreads = factory(Thread::class, 5)->create();
        
        $threads = $this->actingAs($this->user)
            ->getJson(route('profile'))
            ->json();

        $this->assertEquals($ownThreads->toArray(), $threads);
    }
}

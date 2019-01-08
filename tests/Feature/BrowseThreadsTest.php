<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class BrowseThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function unauthenticated_users_cannot_browse_tests()
    {
        $this->withExceptionHandling();

        $this->getJson('threads')
            ->assertStatus(401);
    }

    /** @test */
    public function users_can_browse_threads()
    {
        // Create some threads
        factory(Thread::class, $total = 50)->create();

        // Get the paginated list of threads
        $threads = $this->actingAs($this->user)
            ->getJson(route('threads'))
            ->assertStatus(200)
            ->json();

        $this->assertEquals($total, $threads['total']);
        $this->assertCount(5, $threads['data']);
    }

    /** @test */
    public function threads_are_sorted_by_the_creation_date_newest_first_by_default()
    {
        // Create some threads
        $dbThreads = factory(Thread::class, 5)->create();

        // Get threads
        $pageThreads = $this->actingAs($this->user)
            ->getJson(route('threads'))
            ->json()['data'];

        // Assert threads are sorted by the creation date by default
        $dbThreads = $dbThreads->sortByDesc('id')->pluck('id')->toArray();
        $pageThreads = collect($pageThreads)->pluck('id')->toArray();

        $this->assertEquals($dbThreads, $pageThreads);
    }

    /** @test */
    public function threads_can_be_sorted_alphabetically()
    {
        // Create some threads
        $dbThreads = factory(Thread::class, 5)->create();

        // Get threads sorted alphabetically
        $pageThreads = $this->actingAs($this->user)
            ->getJson(route('threads') . '?sort=abc')
            ->json()['data'];

        // Assert threads are sorted by the creation date by default
        $dbThreads = $dbThreads->sortBy('title')->pluck('id')->toArray();
        $pageThreads = collect($pageThreads)->pluck('id')->toArray();

        $this->assertEquals($dbThreads, $pageThreads);
    }

    /** @test */
    public function threads_can_be_filtered_by_authors()
    {
        // Create some threads. Each thread has a different author.
        $dbThreads = factory(Thread::class, 5)->create();

        $authors = $dbThreads->sortByDesc('id')
            ->pluck('created_by')
            ->take(2)
            ->toArray();

        // Get threads for the first 2 authors
        $pageThreads = $this->actingAs($this->user)
            ->getJson(route('threads') . '?authors=' . implode(',', $authors))
            ->json()['data'];

        // Assert threads are sorted by the creation date by default
        $this->assertCount(2, $pageThreads);

        $pageThreads = collect($pageThreads)->pluck('created_by')->toArray();

        $this->assertEquals($authors, $pageThreads);
    }
}

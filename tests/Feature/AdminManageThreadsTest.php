<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;

class AdminManageThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'is_admin' => true,
        ]);
    }

    /** @test */
    public function admins_can_browse_all_the_threads_in_the_admin_panel()
    {
        // Create some threads
        factory(Thread::class, $total = 50)->create();

        // Get the paginated list of threads
        $threads = $this->actingAs($this->admin)
            ->getJson(route('admin'))
            ->assertStatus(200)
            ->json();

        $this->assertEquals($total, $threads['total']);
        $this->assertCount(5, $threads['data']);
    }

    /** @test */
    public function admins_can_delete_any_threads_in_the_admin_panel()
    {
        $thread = factory(Thread::class)->create();

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

        $this->actingAs($this->admin)
            ->deleteJson(route('admin.destroy', $thread))
            ->assertStatus(200);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_own_profiles()
    {
        $this->actingAs($this->user)
            ->get(route('profile'))
            ->assertStatus(200)
            ->assertSeeText($this->user->name)
            ->assertSeeText($this->user->email);
    }
}

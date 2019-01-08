<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AccessAdminPanelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admins_can_access_the_admin_panel()
    {
        $admin = factory(User::class)->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin'))
            ->assertStatus(200);
    }

    /** @test */
    public function regular_users_cannot_access_the_admin_panel()
    {
        $this->withExceptionHandling();
        
        $this->actingAs($this->user)
            ->get(route('admin'))
            ->assertStatus(404);
    }
}

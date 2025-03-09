<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt; // S'assurer que cet espace de noms est correct et que Volt est bien installé
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        Volt::test('pages.auth.login')->assertSee('Connexion');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        Volt::test('pages.auth.login')
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        Volt::test('pages.auth.login')
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors();

        $this->assertGuest();
    }

    public function test_navigation_menu_can_be_rendered(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertOk();
        Volt::test('layout.navigation')->assertSee('Déconnexion');
    }

    public function test_users_can_logout(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        Volt::test('layout.navigation')
            ->call('logout')
            ->assertHasNoErrors();

        $this->assertGuest();
    }
}

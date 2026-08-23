<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_contains_expected_fields(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="remember"', false);
    }

    public function test_register_page_shows_role_selection_options(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertSee('Pilih Jenis Akun', false);
        $response->assertSee('Pelanggan', false);
        $response->assertSee('Penyedia Jasa', false);
    }

    public function test_customer_registration_form_has_required_fields(): void
    {
        $response = $this->get('/register/pelanggan');

        $response->assertOk();
        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
        $response->assertSee('name="role"', false);
        $response->assertSee('Pelanggan', false);
    }

    public function test_provider_registration_form_has_required_fields(): void
    {
        $response = $this->get('/register/penyedia');

        $response->assertOk();
        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
        $response->assertSee('name="role"', false);
        $response->assertSee('Penyedia Jasa', false);
    }
}

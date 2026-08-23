<?php

namespace Tests\Feature;

use Tests\TestCase;

class MitraCtaTest extends TestCase
{
    public function test_guest_home_page_directs_mitra_cta_to_register_flow(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Daftar Jadi Mitra');
        $response->assertSee(route('register'));
        $response->assertDontSee('TemuJasa.openMitraModal()');
    }
}

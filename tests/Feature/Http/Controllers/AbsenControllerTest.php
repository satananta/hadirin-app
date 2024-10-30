<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Absen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AbsenController
 */
class AbsenControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $absens = Absen::factory()->count(3)->create();

        $response = $this->get(route('absen.index'));

        $response->assertOk();
        $response->assertViewIs('absen.index');
        $response->assertViewHas('absens');
    }
}

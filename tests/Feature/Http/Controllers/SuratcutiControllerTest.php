<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Suratcuti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SuratcutiController
 */
class SuratcutiControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $suratcutis = Suratcuti::factory()->count(3)->create();

        $response = $this->get(route('suratcuti.index'));

        $response->assertOk();
        $response->assertViewIs('suratcuti.index');
        $response->assertViewHas('suratcutis');
    }


    /**
     * @test
     */
    public function show_displays_view()
    {
        $suratcuti = Suratcuti::factory()->create();

        $response = $this->get(route('suratcuti.show', $suratcuti));

        $response->assertOk();
        $response->assertViewIs('suratcuti.show');
        $response->assertViewHas('suratcuti');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SuratcutiController::class,
            'update',
            \App\Http\Requests\SuratcutiUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects()
    {
        $suratcuti = Suratcuti::factory()->create();
        $keterangan = $this->faker->text;
        $tanggal_awal = $this->faker->date();
        $tanggal_akhir = $this->faker->date();
        $status = $this->faker->randomElement(/** enum_attributes **/);
        $user = User::factory()->create();

        $response = $this->put(route('suratcuti.update', $suratcuti), [
            'keterangan' => $keterangan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => $status,
            'user_id' => $user->id,
        ]);

        $suratcuti->refresh();

        $response->assertRedirect(route('suratcuti.index'));
        $response->assertSessionHas('suratcuti.id', $suratcuti->id);

        $this->assertEquals($keterangan, $suratcuti->keterangan);
        $this->assertEquals(Carbon::parse($tanggal_awal), $suratcuti->tanggal_awal);
        $this->assertEquals(Carbon::parse($tanggal_akhir), $suratcuti->tanggal_akhir);
        $this->assertEquals($status, $suratcuti->status);
        $this->assertEquals($user->id, $suratcuti->user_id);
    }
}

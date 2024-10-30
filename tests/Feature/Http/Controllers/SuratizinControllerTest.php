<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Suratizin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SuratizinController
 */
class SuratizinControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $suratizins = Suratizin::factory()->count(3)->create();

        $response = $this->get(route('suratizin.index'));

        $response->assertOk();
        $response->assertViewIs('suratizin.index');
        $response->assertViewHas('suratizins');
    }


    /**
     * @test
     */
    public function show_displays_view()
    {
        $suratizin = Suratizin::factory()->create();

        $response = $this->get(route('suratizin.show', $suratizin));

        $response->assertOk();
        $response->assertViewIs('suratizin.show');
        $response->assertViewHas('suratizin');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SuratizinController::class,
            'update',
            \App\Http\Requests\SuratizinUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects()
    {
        $suratizin = Suratizin::factory()->create();
        $keterangan = $this->faker->text;
        $file_izin = $this->faker->text;
        $tanggal_izin = $this->faker->date();
        $status = $this->faker->randomElement(/** enum_attributes **/);
        $user = User::factory()->create();

        $response = $this->put(route('suratizin.update', $suratizin), [
            'keterangan' => $keterangan,
            'file_izin' => $file_izin,
            'tanggal_izin' => $tanggal_izin,
            'status' => $status,
            'user_id' => $user->id,
        ]);

        $suratizin->refresh();

        $response->assertRedirect(route('suratizin.index'));
        $response->assertSessionHas('suratizin.id', $suratizin->id);

        $this->assertEquals($keterangan, $suratizin->keterangan);
        $this->assertEquals($file_izin, $suratizin->file_izin);
        $this->assertEquals(Carbon::parse($tanggal_izin), $suratizin->tanggal_izin);
        $this->assertEquals($status, $suratizin->status);
        $this->assertEquals($user->id, $suratizin->user_id);
    }
}

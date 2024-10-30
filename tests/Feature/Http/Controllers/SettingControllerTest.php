<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SettingController
 */
class SettingControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $settings = Setting::factory()->count(3)->create();

        $response = $this->get(route('setting.index'));

        $response->assertOk();
        $response->assertViewIs('setting.index');
        $response->assertViewHas('settings');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SettingController::class,
            'update',
            \App\Http\Requests\SettingUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects()
    {
        $setting = Setting::factory()->create();
        $jam_masuk = $this->faker->time();
        $max_jam_masuk = $this->faker->time();
        $jam_keluar = $this->faker->time();
        $lat = $this->faker->latitude;
        $long = $this->faker->randomFloat(/** decimal_attributes **/);
        $radius = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('setting.update', $setting), [
            'jam_masuk' => $jam_masuk,
            'max_jam_masuk' => $max_jam_masuk,
            'jam_keluar' => $jam_keluar,
            'lat' => $lat,
            'long' => $long,
            'radius' => $radius,
        ]);

        $setting->refresh();

        $response->assertRedirect(route('setting.index'));
        $response->assertSessionHas('setting.id', $setting->id);

        $this->assertEquals($jam_masuk, $setting->jam_masuk);
        $this->assertEquals($max_jam_masuk, $setting->max_jam_masuk);
        $this->assertEquals($jam_keluar, $setting->jam_keluar);
        $this->assertEquals($lat, $setting->lat);
        $this->assertEquals($long, $setting->long);
        $this->assertEquals($radius, $setting->radius);
    }
}

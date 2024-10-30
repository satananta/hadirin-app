<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('user.index'));

        $response->assertOk();
        $response->assertViewIs('user.index');
        $response->assertViewHas('users');
    }


    /**
     * @test
     */
    public function create_displays_view()
    {
        $response = $this->get(route('user.create'));

        $response->assertOk();
        $response->assertViewIs('user.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'store',
            \App\Http\Requests\UserStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $nip = $this->faker->word;
        $nama = $this->faker->word;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;
        $tanggal_lahir = $this->faker->date();
        $photo = $this->faker->text;
        $jabatan = $this->faker->randomElement(/** enum_attributes **/);
        $level = $this->faker->randomElement(/** enum_attributes **/);

        $response = $this->post(route('user.store'), [
            'nip' => $nip,
            'nama' => $nama,
            'email' => $email,
            'password' => $password,
            'tanggal_lahir' => $tanggal_lahir,
            'photo' => $photo,
            'jabatan' => $jabatan,
            'level' => $level,
        ]);

        $users = User::query()
            ->where('nip', $nip)
            ->where('nama', $nama)
            ->where('email', $email)
            ->where('password', $password)
            ->where('tanggal_lahir', $tanggal_lahir)
            ->where('photo', $photo)
            ->where('jabatan', $jabatan)
            ->where('level', $level)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('user.id', $user->id);
    }


    /**
     * @test
     */
    public function show_displays_view()
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.show', $user));

        $response->assertOk();
        $response->assertViewIs('user.show');
        $response->assertViewHas('user');
    }


    /**
     * @test
     */
    public function edit_displays_view()
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.edit', $user));

        $response->assertOk();
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'update',
            \App\Http\Requests\UserUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects()
    {
        $user = User::factory()->create();
        $nip = $this->faker->word;
        $nama = $this->faker->word;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;
        $tanggal_lahir = $this->faker->date();
        $photo = $this->faker->text;
        $jabatan = $this->faker->randomElement(/** enum_attributes **/);
        $level = $this->faker->randomElement(/** enum_attributes **/);

        $response = $this->put(route('user.update', $user), [
            'nip' => $nip,
            'nama' => $nama,
            'email' => $email,
            'password' => $password,
            'tanggal_lahir' => $tanggal_lahir,
            'photo' => $photo,
            'jabatan' => $jabatan,
            'level' => $level,
        ]);

        $user->refresh();

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('user.id', $user->id);

        $this->assertEquals($nip, $user->nip);
        $this->assertEquals($nama, $user->nama);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($password, $user->password);
        $this->assertEquals(Carbon::parse($tanggal_lahir), $user->tanggal_lahir);
        $this->assertEquals($photo, $user->photo);
        $this->assertEquals($jabatan, $user->jabatan);
        $this->assertEquals($level, $user->level);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('user.destroy', $user));

        $response->assertRedirect(route('user.index'));

        $this->assertModelMissing($user);
    }
}

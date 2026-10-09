<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAksesTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_publik_bisa_diakses_tamu(): void
    {
        $this->get('/')->assertOk()->assertSee('Login');
        $this->get('/absensi/hari-ini')->assertOk()->assertDontSee('Export CSV');
        $this->get('/absensi/rekap')->assertOk()->assertDontSee('Export CSV');
    }

    public function test_halaman_terlindungi_redirect_ke_login(): void
    {
        foreach (['/siswa', '/kelas', '/jurusan', '/rfid-log', '/absensi/export', '/pengaturan', '/pengguna'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_login_dengan_username(): void
    {
        $user = User::factory()->create(['username' => 'operator1', 'password' => 'rahasia123']);

        $this->post('/login', ['username' => 'operator1', 'password' => 'salah'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();

        $this->post('/login', ['username' => 'operator1', 'password' => 'rahasia123'])
            ->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_akun_nonaktif_tidak_bisa_login(): void
    {
        User::factory()->create(['username' => 'nonaktif', 'password' => 'rahasia123', 'aktif' => false]);

        $this->post('/login', ['username' => 'nonaktif', 'password' => 'rahasia123'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_operator_bisa_kelola_data_tapi_tidak_hapus(): void
    {
        $operator = User::factory()->create();
        $jurusan  = Jurusan::create(['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak']);

        $this->actingAs($operator);

        $this->get('/siswa')->assertOk();
        $this->get('/jurusan')->assertOk()->assertDontSee('confirmDelete(', false);
        $this->get('/absensi/rekap')->assertOk()->assertSee('Export CSV');

        $this->delete("/jurusan/{$jurusan->id}")->assertForbidden();
        $this->assertDatabaseHas('jurusan', ['id' => $jurusan->id]);

        $this->get('/pengaturan')->assertForbidden();
        $this->get('/pengguna')->assertForbidden();
        $this->getJson('/wa-gateway/status')->assertForbidden();
    }

    public function test_admin_bisa_hapus_dan_kelola_pengguna(): void
    {
        $admin   = User::factory()->admin()->create();
        $jurusan = Jurusan::create(['kode' => 'TKJ', 'nama' => 'Teknik Komputer Jaringan']);

        $this->actingAs($admin);

        $this->get('/jurusan')->assertOk()->assertSee('confirmDelete(', false);
        $this->delete("/jurusan/{$jurusan->id}")->assertRedirect('/jurusan');
        $this->assertDatabaseMissing('jurusan', ['id' => $jurusan->id]);

        $this->get('/pengguna')->assertOk();
        $this->post('/pengguna', [
            'name' => 'Operator Baru', 'username' => 'OpBaru', 'role' => 'operator',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/pengguna');
        $this->assertDatabaseHas('users', ['username' => 'opbaru', 'role' => 'operator']);
    }

    public function test_admin_tidak_bisa_hapus_atau_turunkan_diri_sendiri(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin);

        $this->delete("/pengguna/{$admin->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        $this->put("/pengguna/{$admin->id}", [
            'name' => $admin->name, 'username' => $admin->username, 'role' => 'operator',
        ])->assertRedirect('/pengguna');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin', 'aktif' => true]);
    }

    public function test_logout(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}

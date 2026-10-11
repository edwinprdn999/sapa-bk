<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\CounselingService;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('email', 'admin@sapa-bk.sch.id')->first()
            ?? User::where('email', 'admin@sapa-bk.ac.id')->first();
    }

    public function test_admin_can_access_dashboard(): void
    {
        // Add an audit log with raw string created_at to verify no diffForHumans error
        AuditLog::create([
            'user_id' => $this->admin->id,
            'action' => 'test_audit_action',
            'entity_type' => 'System',
            'entity_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'created_at' => now()->toDateTimeString(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Panel Administrasi');
    }

    public function test_admin_can_access_users_management(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');

        $responseSiswa = $this->actingAs($this->admin)->get('/admin/users?role=siswa');
        $responseSiswa->assertStatus(200);

        $responseGuru = $this->actingAs($this->admin)->get('/admin/users?role=guru_bk');
        $responseGuru->assertStatus(200);
    }

    public function test_admin_can_toggle_user_status(): void
    {
        $user = User::where('id', '!=', $this->admin->id)->first();
        $initialStatus = $user->status;

        $response = $this->actingAs($this->admin)->post('/admin/users/' . $user->id . '/toggle');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotEquals($initialStatus, $user->status);
    }

    public function test_admin_can_access_services(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/services');
        $response->assertStatus(200);
        $response->assertSee('Katalog Layanan');
    }

    public function test_admin_can_add_new_service(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/services', [
            'name' => 'Layanan Konseling Uji Coba',
            'category' => 'Pengembangan Diri',
            'description' => 'Deskripsi layanan baru untuk uji coba sistem.',
            'icon' => 'psychology',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('counseling_services', [
            'name' => 'Layanan Konseling Uji Coba',
        ]);
    }

    public function test_admin_can_access_audit_logs(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/audit-logs');
        $response->assertStatus(200);
        $response->assertSee('Audit Log');
    }
}

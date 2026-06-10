<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleHardeningTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $petugas;
    private User $warga;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users with different roles
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'nik' => '1234567890123456',
        ]);

        $this->petugas = User::factory()->create([
            'role' => 'petugas',
            'nik' => '1234567890123457',
        ]);

        $this->warga = User::factory()->create([
            'role' => 'warga',
            'nik' => '1234567890123458',
        ]);
    }

    /**
     * Test that Warga cannot access any admin pages (gets 403).
     */
    public function test_warga_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->warga)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test that Petugas can access operational routes but not master data.
     */
    public function test_petugas_can_access_operational_routes_but_not_master_data(): void
    {
        // Operational routes (OK - 200)
        $this->actingAs($this->petugas)->get(route('admin.dashboard'))->assertStatus(200);
        $this->actingAs($this->petugas)->get(route('admin.transaksi'))->assertStatus(200);
        $this->actingAs($this->petugas)->get(route('admin.penukaran'))->assertStatus(200);

        // Master Data routes (403 Forbidden)
        $this->actingAs($this->petugas)->get(route('admin.users'))->assertStatus(403);
        $this->actingAs($this->petugas)->get(route('admin.kategori'))->assertStatus(403);
        $this->actingAs($this->petugas)->get(route('admin.reward'))->assertStatus(403);
        
        $this->actingAs($this->petugas)->get(route('admin.users.create'))->assertStatus(403);
        $this->actingAs($this->petugas)->delete(route('admin.users.destroy', $this->warga->id))->assertStatus(403);
    }

    /**
     * Test that Admin can access all routes.
     */
    public function test_admin_can_access_all_routes(): void
    {
        $this->actingAs($this->admin)->get(route('admin.dashboard'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.transaksi'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.penukaran'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.users'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.kategori'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.reward'))->assertStatus(200);
    }
}

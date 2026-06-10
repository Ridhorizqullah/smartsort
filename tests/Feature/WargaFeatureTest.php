<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteCategory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Reward;
use App\Models\Redemption;
use App\Models\RedemptionDetail;
use App\Models\PointLedger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $warga;
    private User $admin;
    private WasteCategory $wasteCategory;
    private Reward $reward;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'nik' => '1234567890123456',
        ]);

        $this->warga = User::factory()->create([
            'role' => 'warga',
            'nik' => '1234567890123458',
            'saldo_poin' => 1000,
        ]);

        $this->wasteCategory = WasteCategory::create([
            'name' => 'Plastik',
            'price_per_kg' => 100,
            'unit' => 'kg',
        ]);

        $this->reward = Reward::create([
            'name' => 'Beras 1kg',
            'point_cost' => 500,
            'stock' => 10,
        ]);
    }

    /**
     * Test Warga dashboard works.
     */
    public function test_warga_dashboard_displays_correctly(): void
    {
        $response = $this->actingAs($this->warga)->get(route('warga.dashboard'));
        $response->assertStatus(200);
        $response->assertSee($this->warga->name);
    }

    /**
     * Test Warga transaksi page works with details.wasteCategory.
     */
    public function test_warga_transaksi_page_displays_correctly(): void
    {
        // Create a transaction
        $transaction = Transaction::create([
            'user_id' => $this->warga->id,
            'admin_id' => $this->admin->id,
            'total_point' => 200,
            'idempotency_key' => 'test-idempotency-1',
        ]);

        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'waste_category_id' => $this->wasteCategory->id,
            'weight' => 2,
            'price_snapshot' => $this->wasteCategory->price_per_kg,
            'subtotal_point' => 200,
        ]);

        $response = $this->actingAs($this->warga)->get(route('warga.transaksi'));
        $response->assertStatus(200);
        $response->assertSee('Plastik');
        $response->assertSee('200 Poin');
    }

    /**
     * Test Warga katalog page works.
     */
    public function test_warga_katalog_page_displays_correctly(): void
    {
        $response = $this->actingAs($this->warga)->get(route('warga.katalog'));
        $response->assertStatus(200);
        $response->assertSee('Beras 1kg');
    }

    /**
     * Test Warga redemption page works.
     */
    public function test_warga_redemption_page_displays_correctly(): void
    {
        $redemption = Redemption::create([
            'user_id' => $this->warga->id,
            'total_point' => 500,
            'status' => 'pending',
            'idempotency_key' => 'test-idempotency-2',
            'expires_at' => now()->addDays(2),
        ]);

        RedemptionDetail::create([
            'redemption_id' => $redemption->id,
            'reward_id' => $this->reward->id,
            'qty' => 1,
            'point_snapshot' => $this->reward->point_cost,
            'subtotal_point' => 500,
        ]);

        $response = $this->actingAs($this->warga)->get(route('warga.redemption'));
        $response->assertStatus(200);
        $response->assertSee('Beras 1kg');
        $response->assertSee('500 Poin');
    }

    /**
     * Test Warga can submit redemption request.
     */
    public function test_warga_can_submit_redemption_request(): void
    {
        $response = $this->actingAs($this->warga)->post(route('warga.redemption.store'), [
            'reward_id' => $this->reward->id,
            'qty' => 1,
            'idempotency_key' => 'test-idempotency-3',
        ]);

        $response->assertRedirect(route('warga.redemption'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('redemptions', [
            'user_id' => $this->warga->id,
            'status' => 'pending',
            'idempotency_key' => 'test-idempotency-3',
        ]);
    }
}

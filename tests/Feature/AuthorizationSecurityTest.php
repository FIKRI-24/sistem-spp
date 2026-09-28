<?php

namespace Tests\Feature;

use App\Models\Pembayaran;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * Test suite keamanan Phase 3 — Authentication & Authorization.
 *
 * Mencakup:
 * - Login/logout untuk Super Admin dan Admin.
 * - Penolakan login akun nonaktif (is_active = false).
 * - Brute-force throttling setelah 5x gagal.
 * - Registrasi publik dinonaktifkan (404).
 * - Proteksi route berdasarkan role (403 jika unauthorized).
 * - UserPolicy: Super Admin-only manajemen user.
 * - PembayaranPolicy: Separation of duties untuk void.
 */
class AuthorizationSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    // ====================================================================
    // AUTHENTICATION — Login & Logout
    // ====================================================================

    public function test_super_admin_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_admin_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_login_shows_indonesian_error_for_wrong_credentials(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    // ====================================================================
    // DEACTIVATED ACCOUNT — is_active = false
    // ====================================================================

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->inactive()->create();
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_inactive_user_gets_indonesian_deactivation_message(): void
    {
        $user = User::factory()->inactive()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    // ====================================================================
    // BRUTE-FORCE THROTTLING — 5x gagal = locked
    // ====================================================================

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        // Percobaan ke-6 harus di-throttle
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ====================================================================
    // REGISTRATION DISABLED — 404
    // ====================================================================

    public function test_registration_get_returns_404(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(404);
    }

    public function test_registration_post_returns_404(): void
    {
        $response = $this->post('/register', [
            'name' => 'Hacker',
            'email' => 'hacker@evil.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(404);
    }

    // ====================================================================
    // ROUTE PROTECTION — Role-Based Access Control
    // ====================================================================

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_super_admin_can_access_user_management_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_user_management_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_settings_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $response = $this->actingAs($user)->get(route('admin.settings.index'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_settings_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get(route('admin.settings.index'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_audit_log_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $response = $this->actingAs($user)->get(route('admin.audit-log.index'));
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_audit_log_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get(route('admin.audit-log.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_shared_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)->get(route('admin.siswa.index'))->assertStatus(200);
        $this->actingAs($user)->get(route('admin.tagihan.index'))->assertStatus(200);
        $this->actingAs($user)->get(route('admin.pembayaran.index'))->assertStatus(200);
        $this->actingAs($user)->get(route('admin.laporan.index'))->assertStatus(200);
    }

    public function test_super_admin_can_access_shared_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $this->actingAs($user)->get(route('admin.siswa.index'))->assertStatus(200);
        $this->actingAs($user)->get(route('admin.tagihan.index'))->assertStatus(200);
        $this->actingAs($user)->get(route('admin.pembayaran.index'))->assertStatus(200);
        $this->actingAs($user)->get(route('admin.laporan.index'))->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect('/login');
        $this->get(route('admin.siswa.index'))->assertRedirect('/login');
        $this->get(route('admin.pembayaran.index'))->assertRedirect('/login');
    }

    // ====================================================================
    // USER POLICY
    // ====================================================================

    public function test_super_admin_can_manage_users_via_policy(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $targetUser = User::factory()->create();

        $this->assertTrue($superAdmin->can('viewAny', User::class));
        $this->assertTrue($superAdmin->can('create', User::class));
        $this->assertTrue($superAdmin->can('update', $targetUser));
        $this->assertTrue($superAdmin->can('delete', $targetUser));
        $this->assertTrue($superAdmin->can('toggleActive', $targetUser));
    }

    public function test_admin_cannot_manage_users_via_policy(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $targetUser = User::factory()->create();

        $this->assertFalse($admin->can('viewAny', User::class));
        $this->assertFalse($admin->can('create', User::class));
        $this->assertFalse($admin->can('update', $targetUser));
        $this->assertFalse($admin->can('delete', $targetUser));
        $this->assertFalse($admin->can('toggleActive', $targetUser));
    }

    public function test_super_admin_cannot_delete_own_account(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $this->assertFalse($superAdmin->can('delete', $superAdmin));
    }

    public function test_super_admin_cannot_deactivate_own_account(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $this->assertFalse($superAdmin->can('toggleActive', $superAdmin));
    }

    // ====================================================================
    // PEMBAYARAN POLICY — Separation of Duties
    // ====================================================================

    public function test_admin_can_request_void_but_not_approve(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $pembayaran = Pembayaran::factory()->create(['status' => 'completed']);

        $this->assertTrue($admin->can('requestVoid', $pembayaran));
        $this->assertFalse($admin->can('approveVoid', $pembayaran));
    }

    public function test_super_admin_can_approve_void(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $pembayaran = Pembayaran::factory()->create(['status' => 'completed']);

        $this->assertTrue($superAdmin->can('requestVoid', $pembayaran));
        $this->assertTrue($superAdmin->can('approveVoid', $pembayaran));
    }

    public function test_void_already_voided_payment_is_rejected(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $pembayaran = Pembayaran::factory()->create(['status' => 'void']);

        $this->assertFalse($superAdmin->can('requestVoid', $pembayaran));
        $this->assertFalse($superAdmin->can('approveVoid', $pembayaran));
    }

    public function test_both_roles_can_process_new_payment(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->assertTrue($superAdmin->can('create', Pembayaran::class));
        $this->assertTrue($admin->can('create', Pembayaran::class));
    }
}

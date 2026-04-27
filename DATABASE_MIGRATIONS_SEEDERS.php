<?php

/**
 * DATABASE MIGRATIONS & SEEDERS UNTUK PETUGAS SIDEBAR
 *
 * Pastikan table berikut sudah ada di database Anda:
 * 1. users (sudah ada, pastikan ada kolom 'division')
 * 2. laundry_tasks
 * 3. inventory
 * 4. inventory_adjustment_requests
 */

// ================================================================
// MIGRATION 1: Update Users Table (jika belum ada kolom division)
// ================================================================
// File: database/migrations/YYYY_MM_DD_HHMMSS_add_division_to_users_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'division')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('division', [
                    'washing',
                    'setrika',
                    'ironing',
                    'packing',
                    'customer_service',
                    'inventory'
                ])->nullable()->after('role');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('division');
        });
    }
};

// ================================================================
// MIGRATION 2: Create Laundry Tasks Table
// ================================================================
// File: database/migrations/YYYY_MM_DD_HHMMSS_create_laundry_tasks_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laundry_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');

            // Task information
            $table->enum('division', [
                'washing',
                'setrika',
                'packing',
                'inventory'
            ]);
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'rejected'
            ])->default('pending');

            // Task details
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->decimal('progress', 5, 2)->default(0); // 0-100%

            // Timestamps
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('division');
            $table->index('status');
            $table->index('assigned_to');
            $table->index('transaksi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_tasks');
    }
};

// ================================================================
// MIGRATION 3: Inventory Adjustment Requests Table
// ================================================================
// File: database/migrations/YYYY_MM_DD_HHMMSS_create_inventory_adjustment_requests_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_adjustment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Adjustment details
            $table->integer('old_quantity');
            $table->integer('new_quantity');
            $table->enum('adjustment_type', ['add', 'subtract', 'set']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');

            // Additional info
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('inventory_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustment_requests');
    }
};

// ================================================================
// SEEDER: User Seeder untuk Testing Sidebar
// ================================================================
// File: database/seeders/PetugasUserSeeder.php

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasUserSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing test users
        User::whereIn('email', [
            'admin@test.com',
            'washing@test.com',
            'setrika@test.com',
            'packing@test.com',
            'cs@test.com',
            'inventory@test.com',
        ])->delete();

        // Admin User (dapat akses semua menu)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'division' => null, // Admin tidak perlu division
        ]);

        // Staff Washing
        User::create([
            'name' => 'Petugas Washing',
            'email' => 'washing@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'washing',
        ]);

        // Staff Setrika
        User::create([
            'name' => 'Petugas Setrika',
            'email' => 'setrika@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'setrika',
        ]);

        // Staff Packing
        User::create([
            'name' => 'Petugas Packing',
            'email' => 'packing@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'packing',
        ]);

        // Staff Customer Service
        User::create([
            'name' => 'Customer Service Staff',
            'email' => 'cs@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'customer_service',
        ]);

        // Staff Inventory
        User::create([
            'name' => 'Staff Inventory',
            'email' => 'inventory@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'inventory',
        ]);

        $this->command->info('Test users created successfully!');
    }
}

// ================================================================
// CARA MENJALANKAN MIGRATIONS & SEEDERS
// ================================================================
/*

1. Create migrations dari contoh di atas:
   php artisan make:migration add_division_to_users_table
   php artisan make:migration create_laundry_tasks_table
   php artisan make:migration create_inventory_adjustment_requests_table

2. Copy-paste code migration dari file ini ke file yang dibuat

3. Create seeder:
   php artisan make:seeder PetugasUserSeeder

4. Copy-paste code seeder dari file ini ke database/seeders/PetugasUserSeeder.php

5. Run migrations:
   php artisan migrate

6. Run seeder:
   php artisan db:seed --class=PetugasUserSeeder

7. Test login:
   - Email: washing@test.com, Password: password (hanya lihat Washing menu)
   - Email: packing@test.com, Password: password (hanya lihat Packing menu)
   - Email: admin@test.com, Password: password (lihat semua menu)

*/

// ================================================================
// FACTORY UNTUK TESTING (OPTIONAL)
// ================================================================
// File: database/factories/LaundryTaskFactory.php

<?php

namespace Database\Factories;

use App\Models\LaundryTask;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaundryTaskFactory extends Factory
{
    protected $model = LaundryTask::class;

    public function definition(): array
    {
        return [
            'transaksi_id' => Transaksi::factory(),
            'assigned_to' => User::where('role', 'staff')->inRandomOrder()->first()?->id,
            'division' => $this->faker->randomElement(['washing', 'setrika', 'packing']),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'quantity' => $this->faker->numberBetween(5, 30),
            'notes' => $this->faker->paragraph(),
            'progress' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'progress' => 0,
            'started_at' => null,
            'completed_at' => null,
        ]);
    }

    public function inProgress(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'progress' => $this->faker->numberBetween(1, 99),
            'started_at' => now()->subHours($this->faker->numberBetween(1, 8)),
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'progress' => 100,
            'started_at' => now()->subHours($this->faker->numberBetween(2, 12)),
            'completed_at' => now(),
        ]);
    }
}

// ================================================================
// RUNNING TEST DATA (OPTIONAL)
// ================================================================
// php artisan tinker
// LaundryTask::factory(20)->pending()->create();
// LaundryTask::factory(15)->inProgress()->create();
// LaundryTask::factory(10)->completed()->create();

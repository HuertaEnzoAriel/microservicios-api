<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL','admin@example.com')],
            ['name' => 'Administrador', 'password' => bcrypt(env('ADMIN_PASSWORD'))]
        );

        $admin->assignRole('admin');

        $this->call(CategoriesSeeders::class);
        $this->call(CustomerSeeder::class);
        $this->call(ProductSeeder::class);

    }
}

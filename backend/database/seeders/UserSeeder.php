<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->create([
                'id' => 1,
                'name'     => 'test',
                'email'    => 'test@example.com',
                'password' => bcrypt('123'),
                'roleId' => 1,
            ]);
        }
    }
}

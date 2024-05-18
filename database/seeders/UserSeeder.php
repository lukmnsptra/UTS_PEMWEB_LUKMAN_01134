<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->id = 1;
        $user->name = 'Admin';
        $user->email = 'admin@localhost';
        $user->password = Hash::make('admin1234');
        $user->role = 'admin';
        $user->save();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Jean Dupont',
                'email' => 'jean.dupont@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'Jeanne Martin',
                'email' => 'jeanne.martin@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'Michel Laurent',
                'email' => 'michel.laurent@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'Sophie Bernard',
                'email' => 'sophie.bernard@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'David Moreau',
                'email' => 'david.moreau@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'Émilie Dubois',
                'email' => 'emilie.dubois@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'Robert Petit',
                'email' => 'robert.petit@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'Isabelle Leroy',
                'email' => 'isabelle.leroy@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'Christophe Roy',
                'email' => 'christophe.roy@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => true,
            ],
            [
                'name' => 'Amanda White',
                'email' => 'amanda.white@example.com',
                'password' => Hash::make('password123'),
                'role' => UserRole::CLIENT,
                'is_active' => false, // utilisateur inactif
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

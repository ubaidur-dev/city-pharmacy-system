<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/** Known logins for development and testing. Refuses to run outside the local environment. */
class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            $this->command?->error('TestUsersSeeder only runs in the local environment.');

            return;
        }

        foreach ([
            ['Test Owner', 'owner@test.local', 'owner'],
            ['Test Cashier', 'cashier@test.local', 'cashier'],
        ] as [$name, $email, $role]) {
            $user = User::updateOrCreate(['email' => $email], ['name' => $name, 'password' => 'Test@12345']);
            $user->forceFill(['role' => $role])->save();
        }
    }
}

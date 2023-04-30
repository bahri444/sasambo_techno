<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // insert record for database seeders
        $date_time = Carbon::now()->toDateTimeString();
        $token = Str::random(64);
        $data = array(
            [
                'name' => 'super admin',
                'email' => 'sasambotechno@gmail.com',
                'email_verified_at' => $date_time,
                'password' => Hash::make('123456'),
                'role' => 'superadmin',
                'token' => $token,
                'is_email_verified' => 1,
            ]
        );
        User::insert($data);
    }
}

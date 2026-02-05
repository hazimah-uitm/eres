<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Hazimah',
                'staff_id' => '111111',
                'email' => 'hazimahpethie@gmail.com',
                'password' => Hash::make('user123'),
                'position_id' => 1,
                'ptj_id'        => 1,                  // make sure ptj with id=1 exists
                'campus_id'     => 2,                  // make sure campus with id=2 exists
                'phone_no'  => '082111111',        // nullable|string
                'user_type'     => 'staf pentadbiran',    // required|string
                'publish_status' => true,                  // required|in:1,0
                'email_verified_at' => now(),
            ]
        ]);
    }
}

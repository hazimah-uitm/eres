<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);

        $superadmin = User::create([
            'email' => 'hazimahpte@gmail.com',
            'name' => 'Super Admin',
            'staff_id' => '100001',
            'password' => Hash::make('superadmin123'),
            'position_id' => 1,
            'ptj_id'        => 1,                  // make sure ptj with id=1 exists
            'campus_id'     => 2,                  // make sure campus with id=2 exists
            'phone_no'  => '082111111',        // nullable|string
            'user_type'     => 'staf pentadbiran',    // required|string
            'publish_status' => true,                  // required|in:1,0
            'email_verified_at' => now(),
        ]);

        $superadmin->assignRole($superadminRole);

        $this->assignPermissionsToSuperAdmin();
    }

    /**
     * Assign all permissions to the Super Admin role.
     */
    protected function assignPermissionsToSuperAdmin()
    {
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $superadminRole->syncPermissions(Permission::all());
    }
}

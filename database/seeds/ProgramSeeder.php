<?php

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Program::insert([
            [
                'ptj_id' => 3,
                'kod' => 'CDCS110',
                'name' => 'DIPLOMA SAINS KOMPUTER',
                'publish_status' => true
            ]
        ]);
    }
}

<?php

use App\Models\Kursus;
use Illuminate\Database\Seeder;

class KursusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kursus::insert([
            [
                'program_id' => 1,
                'kod' => 'C0001',
                'name' => 'TESTING1',
                'publish_status' => true
            ]
        ]);
    }
}

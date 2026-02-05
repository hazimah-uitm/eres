<?php

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::insert([
            [
                'title' => 'Pegawai Teknologi Maklumat',
                'grade' => 'F9',
                'publish_status' => true
            ],
            [
                'title' => 'Penolong Pendaftar Kanan',
                'grade' => 'N10',
                'publish_status' => true
            ],
            [
                'title' => 'Penolong Pendaftar',
                'grade' => 'N9',
                'publish_status' => true
            ],
            [
                'title' => 'Pensyarah',
                'grade' => 'DS11',
                'publish_status' => true
            ],
            [
                'title' => 'Pensyarah Kanan',
                'grade' => 'DS13',
                'publish_status' => true
            ],
            [
                'title' => 'Pensyarah Kanan Khas',
                'grade' => 'SKU3',
                'publish_status' => true
            ],
            [
                'title' => 'Profesor',
                'grade' => 'VK7',
                'publish_status' => true
            ],
            [
                'title' => 'Profesor Khas',
                'grade' => 'SKU1',
                'publish_status' => true
            ],
            [
                'title' => 'Profesor Madya',
                'grade' => 'DS14',
                'publish_status' => true
            ],
            [
                'title' => 'Profesor Madya Khas',
                'grade' => 'SKU2',
                'publish_status' => true
            ],
        ]);
    }
}

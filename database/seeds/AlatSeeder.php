<?php

use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('machine')->insert([
        	[
                'machine_name' => 'Lovibond',
                'ket' => '-',
            ],
        ]);

        DB::table('sat_ukuran')->insert([
        	[
                'kode_satuan' => '1',
                'nama_sat' => 'Suhu',
                'simbol' => '°C',
                'id_machine' => '1',
            ],
            [
                'kode_satuan' => '5',
                'nama_sat' => 'pH',
                'simbol' => 'pH',
                'id_machine' => '1',
            ],
            [
                'kode_satuan' => '6',
                'nama_sat' => 'Oksigen',
                'simbol' => '%O2',
                'id_machine' => '1',
            ],
            [
                'kode_satuan' => '7',
                'nama_sat' => 'Mikrogram/liter',
                'simbol' => 'mg/L',
                'id_machine' => '1',
            ],
            [
                'kode_satuan' => '13',
                'nama_sat' => 'koefisien gesekan statis',
                'simbol' => 'µs',
                'id_machine' => '1',
            ],
            [
                'kode_satuan' => '14',
                'nama_sat' => 'meter/sekon',
                'simbol' => 'm/s',
                'id_machine' => '1',
            ],
            [
                'kode_satuan' => '18',
                'nama_sat' => 'Megavolt',
                'simbol' => 'mV',
                'id_machine' => '1',
            ],
            [
                'kode_satuan' => '19',
                'nama_sat' => 'parts per million',
                'simbol' => 'ppm',
                'id_machine' => '1',
            ],
        ]);
    }
}

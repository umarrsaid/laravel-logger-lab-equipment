<?php

use Illuminate\Database\Seeder;

class DummyPengukuran extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tgl = \Carbon\Carbon::now()->format('Y-m-d');
        $kolam = array('A1','A2','A3');
        
        // for ($i=0; $i < 23 ; $i++) { 
        //     for ($x=0; $x < 5 ; $x++) { 
                
        //         DB::table('pengukuran')->insert([
        //             [
        //                 'tgl_jam' => $tgl.' '.'00:00:00',
        //                 'value' => rand(50, 70) / 10,
        //                 'id_sat' => 2,
        //                 'kolam' => $kolam[rand(0,2)],
        //                 'alat' => 'lab',
        //             ],
        //         ]);
        //     }
        // }
        $v = array('5.0','5.1','5.2','5.3','5.4','5.5','5.6','5.7','5.8','5.9','6.0','6.1','6.2','6.3','6.4','6.5','6.6','6.7','6.8','6.9','7.0');
        $a = array('5.5','5.6','5.7','5.8','5.9','6.0');
        $y = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24');
        for ($i=0; $i < 24 ; $i++) {
            if ($i >= 0 && $i <= 1) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 2 && $i <= 4) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 5 && $i <= 7 ) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 8 && $i <= 10 ) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 11 && $i <= 12) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 13 && $i <= 15) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 11 && $i <= 12) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 13 && $i <= 15) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 16 && $i <= 17) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 18 && $i <= 20) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 21 && $i <= 22) {
                $val = $v[rand(0,5)];
                $kol = $kolam[rand(0,2)];
            }else if ($i >= 23 && $i <= 24) {
                $val = $v[rand(10,20)];
                $kol = $kolam[rand(0,2)];
            }
            $n = array('05','10','15','20','25','30','35','40','45','50','55','60');
            for ($x=0; $x < 11 ; $x++) { 
                DB::table('pengukuran')->insert([
                    [
                        'tgl_jam' => $tgl.' '.''.$y[$i].':'.$n[$x].':'.rand(10, 59),
                        'value' => $a[rand(0,4)],
                        'id_sat' => 2,
                        'kolam' => $kolam[rand(0,2)],
                        'alat' => 'lab',
                    ],
                ]);
            }
        }
        
    }
}

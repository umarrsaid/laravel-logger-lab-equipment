<?php
namespace App\Classes;
use Carbon\Carbon;
use DB;

class Logger {
    public static function dropdownPengukuran($jenis)
    {
        if ($jenis == 'kolam') {
            $pengukuran = DB::select('SELECT DISTINCT(kolam) as nama FROM pengukuran WHERE deleted_at is null');
        }else{
            $pengukuran = DB::select('SELECT DISTINCT(su.nama_sat) as nama
                                    FROM pengukuran as p
                                    LEFT JOIN sat_ukuran as su on su.id = p.id_sat
                                    WHERE p.deleted_at is null');
        }

        $drop = '';
        foreach ($pengukuran as $key => $value) {
            $drop .='<option value="'.$value->nama.'">'.$value->nama.'</option>';
        }

        return $drop;
    }
}

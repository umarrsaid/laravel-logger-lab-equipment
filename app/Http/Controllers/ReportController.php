<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    //
    public function cetakData($id_user = null,$tgl_awal = null,$tgl_akhir = null, $satuan = null, $kolam = null)
    {
        $user_name = \DB::select('SELECT `name` from users where id ='.$id_user.'')[0]->name;
        if ($user_name == 'SuperUser' ) {
            $name = '';
        }else{
            $name = 'and p.alat="'.$user_name.'"';
        }

        $tgl_awal = \Carbon\Carbon::parse($tgl_awal)->format('Y-m-d H:i:s');
        $tgl_akhir = \Carbon\Carbon::parse($tgl_akhir)->format('Y-m-d H:i:s');

        if ($satuan != 'all') {
            $satuan = 'and su.nama_sat="'.$satuan.'" ';
        }else{
            $satuan = '';
        }

        if ($kolam != 'all') {
            $kolam = 'and p.kolam="'.$kolam.'" ';
        }else{
            $kolam = '';
        }

        $log = \DB::select('SELECT p.id, p.tgl_jam, p.value,p.id_sat,p.kolam,p.alat,su.nama_sat,su.simbol
                            from pengukuran as p
                            left join sat_ukuran as su on su.id = p.id_sat
                            where p.tgl_jam BETWEEN "'.$tgl_awal.'" and "'.$tgl_akhir.'" and p.deleted_at is null  '.$name.' '.$satuan.' '.$kolam.'  order by p.tgl_jam desc ');

        return view('pages.report.data-activity',compact('log'));
    }
}

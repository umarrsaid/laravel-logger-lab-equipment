<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ActivityController extends Controller
{
    //
    public function pagesDataLog()
    {
        return view('pages.activity.data_logging');
    }

    public function tableDataLog($id_user = null,$tgl_awal = null,$tgl_akhir = null,$satuan = null,$kolam = null)
    {
        $user_name = \DB::select('SELECT `name` from users where id ='.$id_user.'')[0]->name;
        if ($user_name == 'SuperUser' ) {
            $name = '';
        }else{
            $name = 'and p.alat="'.$user_name.'"';
        }

        $tgl = '';

        if (isset($tgl_akhir) || $tgl_akhir != null) {
            $tgl_awal = \Carbon\Carbon::parse($tgl_awal)->format('Y-m-d H:i:s');
            $tgl_akhir = \Carbon\Carbon::parse($tgl_akhir)->format('Y-m-d H:i:s');

            $tgl = 'and p.tgl_jam BETWEEN "'.$tgl_awal.'" and "'.$tgl_akhir.'" ';
        }

        if ($satuan != 'all' && $satuan != null) {
            $satuan = str_replace('_','/',$satuan);
            $satuan = 'and su.nama_sat="'.$satuan.'" ';
        }else{
            $satuan = '';
        }

        if ($kolam != 'all' && $kolam != null) {
            $kolam = str_replace('_','/',$kolam);
            $kolam = 'and p.kolam="'.$kolam.'" ';
        }else{
            $kolam = '';
        }

        $log = \DB::select('SELECT p.id, p.tgl_jam, p.value,p.id_sat,p.kolam,p.alat,su.nama_sat
                            from pengukuran as p
                            left join sat_ukuran as su on su.id = p.id_sat
                            where p.deleted_at is null '.$name.' '.$tgl.' '.$satuan.' '.$kolam.'  order by p.tgl_jam desc ');

        $result = DataTables::of($log);

        $result->addColumn('satuan', function($data) {
            return $data->nama_sat;
        });

        $result->addColumn('value', function($data) {
            return number_format($data->value,2,',','');
        });

        return $result->addIndexColumn()->make(true);
    }

    public function reportLogger(Request $request)
    {
        $data['data'] = $request->all();

        $link = url('/logger/cetak/'.$data['data']['id_user'].'/'.$data['data']['tgl_awal'].'/'.$data['data']['tgl_akhir'].'/'.$data['data']['satuan'].'/'.$data['data']['kolam']);

        return response()->json(['link'=>$link],200);
    }

    public function deleteDataLogging(Request $request)
    {
        $tgl_now = \Carbon\Carbon::now();
        $tgl_awal = \Carbon\Carbon::parse($request->tgl_awal)->format('Y-m-d H:i:s');
        $tgl_akhir = \Carbon\Carbon::parse($request->tgl_akhir)->format('Y-m-d H:i:s');

        $kolam = '';
        $satuan = '';

        $user = \DB::select('SELECT `name` FROM users WHERE id='.$request->id_user.' ');

        if ($request->satuan != 'all') {
            $sat = \DB::select('SELECT id FROM sat_ukuran WHERE nama_sat="'.$request->satuan.'"');
            $satuan = 'and id_sat="'.$sat[0]->id.'"';
        }

        if ($request->kolam != 'all') {
           $kolam = 'and kolam="'.$request->kolam.'"';
        }

        if ($user[0]->name == 'SuperUser' ) {
            $name = '';
        }else{
            $name = 'and alat="'.$user[0]->name.'"';
        }

        $tgl = 'and tgl_jam BETWEEN "'.$tgl_awal.'" and "'.$tgl_akhir.'" ';

        $log = \DB::update('UPDATE pengukuran SET deleted_at ="'.$tgl_now.'"
                            where deleted_at is null '.$name.' '.$tgl.' '.$satuan.' '.$kolam.'');
        return response()->json(['msg'=>'Data Berhasil Di Hapus'],200);
    }

    public function getDataKolam(Request $request)
    {
        $user_name = \DB::select('SELECT `name` from users where id ='.$request->id_user.'')[0]->name;
        if ($user_name == 'SuperUser' ) {
            $name = '';
        }else{
            $name = 'and p.alat="'.$user_name.'"';
        }

        $satuan = $request->satuan;

        if ($satuan != 'all' && $satuan != null) {
            $satuan = str_replace('_','/',$satuan);
            $satuan = 'and su.nama_sat="'.$satuan.'" ';
        }else{
            $satuan = '';
        }

        $log = \DB::select('SELECT DISTINCT(p.kolam) as kolam
                            from pengukuran as p
                            left join sat_ukuran as su on su.id = p.id_sat
                            where p.deleted_at is null '.$name.' '.$satuan.' ');

        $drop = '';
        foreach ($log as $key => $value) {
            $drop .='<option value="'.$value->kolam.'">'.$value->kolam.'</option>';
        }

        return response()->json(['data'=>$drop],200);
    }
}

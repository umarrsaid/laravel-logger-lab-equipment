<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Hash;
use Auth;
use DateTime;
use DateInterval;
class ApiController extends Controller
{
    //
    public function postDataAlat(Request $request)
    {
        // dd($request->all());
        try {
            $exception = \DB::transaction(function() use($request) {
                // $api_secret = $request->api_auth['api_secret'];
                // $api_key = $request->api_auth['api_key'];
                // $cek_auth = \DB::select('SELECT api_secret,api_key from api_auth where api_secret = "'.$api_secret.'" and api_key ="'.$api_key.'" ');
                // $tgl_jam = $request->alat['tgl_jam'];
                // $value = $request->alat['value'];
                // $id_sat = $request->alat['id_sat'];
                // $kolam = $request->alat['kolam'];
                // $alat = $request->alat['alat'];
                // $tgl = \Carbon\Carbon::now();
                // if ($cek_auth) {
                //     $cek_satuan = \DB::select('SELECT kode_satuan,nama_sat,simbol from sat_ukuran where id ='.$id_sat.' ');


                // }else{
                //     return false;
                // }

                $cek_sat = \DB::select('SELECT id, kode_satuan from sat_ukuran where kode_satuan ='.$request->id_sat.' ');
                $id_sat = 0;
                if ($cek_sat) {
                    $id_sat = $cek_sat[0]->id;
                }

                $tgl_jam = $request->tgl_jam;
                $value = $request->value;
                $id_sat = $id_sat;
                $kolam = $request->kolam;
                $alat = $request->alat;
                $tgl = \Carbon\Carbon::now();
                \DB::insert("INSERT INTO pengukuran(tgl_jam,`value`,id_sat,kolam,alat,created_at) VALUES ('$tgl_jam','$value','$id_sat','$kolam','$alat','$tgl') ");
            });

            if(is_null($exception)) {
                $msg = 'Berhasil menambah data';
                return response()->json(['msg'=>$msg]);
            } else {
                return response()->json(['msg' => 'Alat tidak terdaftar'], 500);
                throw new Exception;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function loginLogger(Request $request)
    {
        // dd($request->all());
        $tgl = \Carbon\Carbon::now();
        $cek_user = \DB::SELECT("SELECT id,`name`,`password`,api_token FROM users WHERE `name` = '$request->username'");
        $valid = false;
        if (count($cek_user) == 0 ) {
            $status = 401;
            $msg = 'Username tidak terdaftar';
        }else{
            $status = 401;
            if ($cek_user && Hash::check($request->password, $cek_user[0]->password)) {
                $cek_imei = \DB::select('SELECT imei from logger where imei ="'.$request->imei.'" and id_user ='.$cek_user[0]->id.'  ');
                if ($cek_imei) {
                    $status = 200;
                    $valid = true;
                    $token = $cek_user[0]->api_token;
                    $cek_machine =  \DB::select('SELECT ma.id , m.machine_name
                                                FROM machine_allowed AS ma
                                                LEFT JOIN machine AS m ON m.id = ma.id_machine
                                                LEFT JOIN users AS u ON u.id = ma.id_user
                                                WHERE ma.id_user ='.$cek_user[0]->id.' ');

                    if ($cek_machine) {
                        $msg = 'User , Perangkat dan Alat tersedia';
                    }else{
                        $msg = 'User dan perangkat tersedia';
                    }
                }else{
                    $msg = 'Perangkat ini tidak terdaftar';
                }
            }else{
                $msg = 'Password yang anda masukan salah';
            }
        }

        $cek_machine = isset($cek_machine) ? $cek_machine:null;
        $token = isset($token) ? $token:null;
        $s = ($valid ? 'true':'false');
        \DB::insert("INSERT INTO login_history(tanggal,username,`password`,brand,imei,ip_address,carrier,`status`,keterangan,created_at)
                    VALUES ('$tgl','$request->username','$request->password','$request->brand','$request->imei','$request->ip_address','$request->carrier','$s','$msg','$tgl') ");

        $json = [
            'valid' => $valid,
            'token' => $token,
	        'token_type' => 'Bearer',
            'machine_allowed' => $cek_machine,
            'msg' => $msg,
        ];
        return response()->json($json,$status);
    }

    public function chartPengukuran(Request $request)
    {
        $id_user = $request->id_user;
        $user_name = \DB::select('SELECT `name` from users where id ='.$id_user.'')[0]->name;

        $id_sat = $request->id;
        $data[0]['name'] = '';
        $data[0]['data'] = [];
        $data[0]['type'] = 'spline';

        //tanggal sekarang
        $tanggal = \Carbon\Carbon::parse($request->tgl)->format('Y-m-d');
        //

        //penampilan data pengukuran berdasarkan user yang login
        if ($user_name == 'SuperUser' ) {
            $name = '';
        }else{
            $name = 'and alat="'.$user_name.'"';
        }
        //

        // data kolam berdasarkan satuan
        $kolam = \DB::select('SELECT DISTINCT kolam from pengukuran where id_sat ='.$id_sat.' '.$name.' and deleted_at is null ');
        //

        //menentukan selisih detik
        $waktuawal = date_create($tanggal.' '.$request->jam_awal.':00');
        $waktuakhir = date_create($tanggal.' '.$request->jam_akhir.':00');
        $diff  = date_diff($waktuawal, $waktuakhir);

        $diff_m = $diff->i; //menit
        $diff_j = $diff->h; //jam

        if ($diff_m > 0) {
            $t = $diff_j * 60 + $diff_m;
            $mnt = $t / 24;
        }else{
            $t = $diff_j * 60;
            $mnt = $t / 24;
        }

        $dt = $mnt*60;
        $selisih_detik = $dt;
        //

        $date = date_create($tanggal.' '.$request->jam_awal.':00');
        date_add($date, date_interval_create_from_date_string('- '.$selisih_detik.' seconds'));
        $waktusebelum = date_format($date, 'H:i:s');

        //data selisih detik
        $time = new DateTime($tanggal.''.$waktusebelum);
        for ($i=0; $i <= 24 ; $i++) {
            $time->add(new DateInterval('PT' . $selisih_detik . 'S'));
            $selisih[] = $time->format('H:i:s');
            $waktu[] = $time->format('H:i');
        }
        //

        //data pengukuran sesuai dengan waktu yang di tentukan , dengan hasil rata-rata
        $point = array();
        foreach ($kolam as $key => $value) {
            $data[$key]['name'] = $value->kolam;
            foreach ($selisih as $keys => $values) {
                $bt = $keys + 1;
                if ($keys == 24) {
                    $bt = $keys;
                }
                $vals = \DB::select('SELECT SUM(`value`) / COUNT(id) as val from pengukuran where deleted_at is null and id_sat ='.$id_sat.' and kolam="'.$value->kolam.'" and DATE(tgl_jam) ="'.$tanggal.'" and TIME(tgl_jam) BETWEEN "'.$values.'" and "'.$selisih[$bt].'"  '.$name.' ');
                $point[$keys] = ($vals[0]->val ? (float)number_format($vals[0]->val, 1, '.', '') : 0);
            }
            $data[$key]['data'] = array_values($point);
            $data[$key]['type'] = 'spline';
        }
        //

        $dat['jam'] = $waktu;
        $dat['data'] = $data;

        return $dat;
    }

    public function filterLogger($id = null, $id_user = null,$tgl_awl = null, $waktu_aw = null, $tgl_akh = null, $waktu_ak = null)
    {
        $tanggal_awl = \Carbon\Carbon::parse($tgl_awl)->format('Y-m-d');
        $tanggal_akh = \Carbon\Carbon::parse($tgl_akh)->format('Y-m-d');
        $id_sat = $id;
        $user_name = \DB::select('SELECT `name` from users where id ='.$id_user.'')[0]->name;

        //penampilan data pengukuran berdasarkan user yang login
        if ($user_name == 'SuperUser' ) {
            $name = '';
        }else{
            $name = 'and alat="'.$user_name.'"';
        }
        //

        //data kolam berdasarkan satuan
        $kolam = \DB::select('SELECT DISTINCT kolam from pengukuran where id_sat ='.$id_sat.' '.$name.' and deleted_at is null ');
        //

        //format waktu
        if ($waktu_ak) {
            $ex_aw = explode(':',$waktu_aw);
            $ex_ak = explode(':',$waktu_ak);

            if($ex_aw[0] < 10){
                $aw = '0'.$ex_aw[0];
                $ak = $ex_aw[1];
                $waktu_aw = $aw.':'.$ak;
            }

            if($ex_ak[0] < 10) {
                $aw = '0'.$ex_ak[0];
                $ak = $ex_ak[1];
                $waktu_ak = $aw.':'.$ak;
            }
        }
        //

        //penghitungan selisih waktu
        $waktuawal = date_create($tanggal_awl.' '.$waktu_aw.':00');
        $waktuakhir = date_create($tanggal_akh.' '.$waktu_ak.':00');
        $diff  = date_diff($waktuawal, $waktuakhir);
        $diff_m = $diff->i; //menit
        $diff_j = $diff->h; //jam

        if ($diff_m > 0) {
            $t = $diff_j * 60 + $diff_m;
            $mnt = $t / 24;
        }else{
            $t = $diff_j * 60;
            $mnt = $t / 24;
        }

        $dt = $mnt*60;
        $selisih_detik = $dt;
        //

        $date = date_create($tanggal_awl.' '.$waktu_aw.':00');
        date_add($date, date_interval_create_from_date_string('- '.$selisih_detik.' seconds'));
        $waktusebelum = date_format($date, 'H:i:s');

        //data waktu dengan selisih
        $time = new DateTime($tanggal_akh.''.$waktusebelum);
        for ($i=0; $i <= 24 ; $i++) {
            $time->add(new DateInterval('PT' . $selisih_detik . 'S'));
            $selisih[] = $time->format('H:i:s');
            $waktu[] = $time->format('H:i');
        }
        //

        //data pengukuran
        $point = array();
        foreach ($kolam as $key => $value) {
            $data[$key]['name'] = $value->kolam;
            foreach ($selisih as $keys => $values) {
                $bt = $keys + 1;
                if ($keys == 24) {
                    $bt = $keys;
                }
                $vals = \DB::select('SELECT SUM(`value`) / COUNT(id) as val from pengukuran where deleted_at is null and id_sat ='.$id_sat.'
                                    and kolam="'.$value->kolam.'" and DATE(tgl_jam) BETWEEN "'.$tanggal_awl.'" and "'.$tanggal_akh.'"
                                    and TIME(tgl_jam) BETWEEN "'.$values.'" and "'.$selisih[$bt].'"  '.$name.' ');
                $point[$keys] = ($vals[0]->val ? (float)number_format($vals[0]->val, 1, '.', '') : 0);
            }
            $data[$key]['data'] = array_values($point);
            $data[$key]['type'] = 'spline';
        }

        $dat['jam'] = $waktu;
        $dat['data'] = $data;
        return $dat;
    }

    public function dataKolam()
    {
        $kolam = \DB::select('SELECT id,kolam,keterangan from pool where deleted_at is null');

        return $kolam;
    }
}

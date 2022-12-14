<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\User;
use Illuminate\Support\Str;
class DataMasterController extends Controller
{
    //Pages Data Master
    public function pagesMachine()
    {
        return view('pages.data-master.machine');
    }

    public function pagesMeasure()
    {
        return view('pages.data-master.measure');
    }

    public function pagesLogger()
    {
        return view('pages.data-master.logger');
    }

    public function pagesUser()
    {
        return view('pages.data-master.user');
    }

    public function PagesPool()
    {
        return view('pages.data-master.pool');
    }
    //end pages

    //table data master
    public function tableMachine()
    {
        $machine = \DB::select('SELECT id, machine_name, ket from machine where deleted_at is null');

        $result = DataTables::of($machine);

        $result->addColumn('aksi', function($data) {
            $action = '<div class="btn-group">
                            <button class="btn btn-xs default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu">
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" data-name="modal-machine" class="modal-machine">
                                        <i class="icon-pencil"></i> Edit </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" id="delete">
                                        <i class="icon-trash"></i> Delete </a>
                                </li>
                            </ul>
                        </div>';
            return $action;
        });

        return $result->addIndexColumn()->rawColumns(['aksi'])->make(true);
    }

    public function tableMeasure()
    {
        $measure = \DB::select('SELECT su.id, su.kode_satuan, su.nama_sat, su.simbol, su.id_machine, mc.machine_name as machine
                                from sat_ukuran as su
                                left join machine as mc on mc.id = su.id_machine
                                where su.deleted_at is null');

        $result = DataTables::of($measure);

        $result->addColumn('aksi', function($data) {
            $action = '<div class="btn-group">
                            <button class="btn btn-xs default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu">
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" data-name="modal-measure" class="modal-measure">
                                        <i class="icon-pencil"></i> Edit </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" id="delete">
                                        <i class="icon-trash"></i> Delete </a>
                                </li>
                            </ul>
                        </div>';
            return $action;
        });

        $result->addColumn('machine', function($data) {
            return $data->machine;
        });

        return $result->addIndexColumn()->rawColumns(['aksi'])->make(true);
    }

    public function tableLogger()
    {
        $logger = \DB::select('SELECT l.id,l.imei,l.hp,l.ket,u.name
                                from logger as l
                                left join users as u on u.id = l.id_user
                                where l.deleted_at is null');

        $result = DataTables::of($logger);

        $result->addColumn('aksi', function($data) {
            $action = '<div class="btn-group">
                            <button class="btn btn-xs default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu">
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" data-name="modal-logger" class="modal-logger">
                                        <i class="icon-pencil"></i> Edit </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" id="delete">
                                        <i class="icon-trash"></i> Delete </a>
                                </li>
                            </ul>
                        </div>';
            return $action;
        });

        $result->addColumn('user', function($data) {
            return $data->name;
        });

        return $result->addIndexColumn()->rawColumns(['aksi'])->make(true);
    }

    public function tableUser()
    {
        $user = \DB::select('SELECT id,`name`,email from users where deleted_at is null');

        $result = DataTables::of($user);

        $result->addColumn('aksi', function($data) {
            $action = '<div class="btn-group">
                            <button class="btn btn-xs default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu">
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" data-name="modal-user" class="modal-user">
                                        <i class="icon-pencil"></i> Edit </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" id="delete">
                                        <i class="icon-trash"></i> Delete </a>
                                </li>
                            </ul>
                        </div>';
            return $action;
        });

        return $result->addIndexColumn()->rawColumns(['aksi'])->make(true);
    }

    public function tablePool()
    {
        $pool = \DB::select('SELECT id,kolam,keterangan from `pool` where deleted_at is null');

        $result = DataTables::of($pool);

        $result->addColumn('aksi', function($data) {
            $action = '<div class="btn-group">
                            <button class="btn btn-xs default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                <i class="fa fa-angle-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-left" role="menu">
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" data-name="modal-pool" class="modal-pool">
                                        <i class="icon-pencil"></i> Edit </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-id="'.$data->id.'" id="delete">
                                        <i class="icon-trash"></i> Delete </a>
                                </li>
                            </ul>
                        </div>';
            return $action;
        });

        return $result->addIndexColumn()->rawColumns(['aksi'])->make(true);
    }
    //end table

    //modal data master
    public function modal(Request $request)
    {
        switch ($request->name) {
            case 'modal-machine':
                $ket = 'tambah';
                if ($request->id) {
                    $machine = \DB::select('SELECT id,machine_name,ket from machine where id='.$request->id.'')[0];
                    $ket = 'ubah';
                }
                $modal_size   = 'modal-kc';

                $modal_header =  '<i class="fa fa-'.($ket == 'ubah' ?'pencil':'plus').'"></i> '.($ket == 'ubah' ? 'Edit':'Add').' Machine';

                $modal_body   = csrf_field().
                    '<input type="hidden" name="id_machine" value="'.($ket == 'ubah' ? $machine->id:'').'">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Machine Name</label>
                                <input class="form-control" type="text" placeholder="Machine" name="machine" value="'.($ket == 'ubah' ? $machine->machine_name:'').'">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Information</label>
                                <textarea class="form-control" style="resize: none;height : 100px" name="ket">'.($ket == 'ubah' ? $machine->ket:'').'</textarea>
                            </div>
                        </div>
                    </div>';

                $modal_footer = '<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn green" data-ref="'.($ket == 'ubah'?'update':'save').'-machine" id="btn-save">Save</button>';
                $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);

                return $data;
            break;

            case 'modal-measure':
                $ket = 'tambah';
                $id_machine = '';
                if ($request->id) {
                    $measure = \DB::select('SELECT id,kode_satuan,nama_sat,simbol,id_machine from sat_ukuran where id='.$request->id.'')[0];
                    $ket = 'ubah';
                    $id_machine  = $measure->id_machine;
                }

                $machine = \DB::select('SELECT id,machine_name from machine where deleted_at is null');

                // $opt = '';
                foreach ($machine as $key => $value) {
                    $opt[] ='<option value="'.$value->id.'" '.($value->id == $id_machine ?'selected':'' ).'>'.$value->machine_name.'</option>';
                }

                $option = implode(' ',$opt);

                $modal_size   = 'modal-mk';

                $modal_header =  '<i class="fa fa-'.($ket == 'ubah' ?'pencil':'plus').'"></i> '.($ket == 'ubah' ? 'Edit':'Add').' Measure';

                $modal_body   = csrf_field().
                    '<input type="hidden" name="id_measure" value="'.($ket == 'ubah' ? $measure->id:'').'">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Satuan</label>
                                <input class="form-control" type="text" placeholder="Kode Satuan" name="kode_satuan" value="'.($ket == 'ubah' ? $measure->kode_satuan:'').'">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Satuan</label>
                                <input class="form-control" type="text" placeholder="Nama Satuan" name="nama_sat" value="'.($ket == 'ubah' ? $measure->nama_sat:'').'">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Simbol</label>
                                <input class="form-control" type="text" placeholder="Simbol" name="simbol" value="'.($ket == 'ubah' ? $measure->simbol:'').'">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Machine</label>
                                <select name="id_machine" class="form-control">
                                    <option value="" selected disabled>Pilih Machine</option>
                                    '.$option.'
                                </select>
                            </div>
                        </div>
                    </div>';

                $modal_footer = '<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn green" data-ref="'.($ket == 'ubah'?'update':'save').'-measure" id="btn-save">Save</button>';
                $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);

                return $data;
            break;

            case 'modal-logger':
                $ket = 'tambah';
                $id_user = '';
                if ($request->id) {
                    $logger = \DB::select('SELECT id,imei,hp,ket,id_user from logger where id='.$request->id.'')[0];
                    $ket = 'ubah';
                    $id_user = $logger->id_user;
                }

                $opt = '';
                $user = \DB::select('SELECT id,`name` from users where deleted_at is null');
                foreach ($user as $key => $value) {
                    $opt .='<option value="'.$value->id.'" '.($value->id == $id_user ? 'selected':'').'>'.$value->name.'</option>';
                }

                $modal_size   = 'modal-kc';

                $modal_header =  '<i class="fa fa-'.($ket == 'ubah' ?'pencil':'plus').'"></i> '.($ket == 'ubah' ? 'Edit':'Add').' Logger';

                $modal_body   = csrf_field().
                    '<input type="hidden" name="id_logger" value="'.($ket == 'ubah' ? $logger->id:'').'">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>IMEI</label>
                                <input class="form-control" type="text" placeholder="IMEI" name="imei" value="'.($ket == 'ubah' ? $logger->imei:'').'">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Handphone</label>
                                <input class="form-control" type="text" placeholder="Handphone" name="hp" value="'.($ket == 'ubah' ? $logger->hp:'').'">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>User</label>
                                <select name="id_user" class="form-control">
                                    <option value="" selected disabled>Pilih User</option>
                                    '.$opt.'
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Information</label>
                                <textarea class="form-control" style="resize: none;height : 100px" name="ket">'.($ket == 'ubah' ? $logger->ket:'').'</textarea>
                            </div>
                        </div>
                    </div>';

                $modal_footer = '<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn green" data-ref="'.($ket == 'ubah'?'update':'save').'-logger" id="btn-save">Save</button>';
                $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);

                return $data;
            break;

            case 'modal-user':
                $ket = 'tambah';
                $pass  = ' <div class="col-md-12">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" placeholder="" name="password">
                                </div>
                            </div>';
                if ($request->id) {
                    $user = \DB::select('SELECT id,`name`,email from users where id='.$request->id.'')[0];
                    $ket = 'ubah';
                    $pass = '';
                }
                $modal_size   = 'modal-kc';

                $modal_header =  '<i class="fa fa-'.($ket == 'ubah' ?'pencil':'plus').'"></i> '.($ket == 'ubah' ? 'Edit':'Add').' User';

                $modal_body   = csrf_field().
                    '<input type="hidden" name="id_user" value="'.($ket == 'ubah' ? $user->id:'').'">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>User Name</label>
                                <input class="form-control" type="text" placeholder="User Name" name="name" value="'.($ket == 'ubah' ? $user->name:'').'">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" type="text" placeholder="Email" name="email" value="'.($ket == 'ubah' ? $user->email:'').'">
                            </div>
                        </div>
                        '.$pass.'
                    </div>';

                $modal_footer = '<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn green" data-ref="'.($ket == 'ubah'?'update':'save').'-user" id="btn-save">Save</button>';
                $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);

                return $data;
            break;

            case 'modal-pool':
                $ket = 'tambah';
                if ($request->id) {
                    $kolam = \DB::select('SELECT id,kolam,keterangan from `pool` where id='.$request->id.'')[0];
                    $ket = 'ubah';
                    $pass = '';
                }
                $modal_size   = 'modal-kc';

                $modal_header =  '<i class="fa fa-'.($ket == 'ubah' ?'pencil':'plus').'"></i> '.($ket == 'ubah' ? 'Edit':'Add').' Location';

                $modal_body   = csrf_field().
                    '<input type="hidden" name="id_kolam" value="'.($ket == 'ubah' ? $kolam->id:'').'">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Location</label>
                                <input class="form-control" type="text" placeholder="Location" name="kolam" value="'.($ket == 'ubah' ? $kolam->kolam:'').'">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <input class="form-control" type="text" placeholder="Description" name="keterangan" value="'.($ket == 'ubah' ? $kolam->keterangan:'').'">
                            </div>
                        </div>
                    </div>';

                $modal_footer = '<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn green" data-ref="'.($ket == 'ubah'?'update':'save').'-pool" id="btn-save">Save</button>';
                $data = array('modal_size' => $modal_size,'modal_header' => $modal_header, 'modal_body' => $modal_body, 'modal_footer' => $modal_footer);

                return $data;
            break;
        }
    }
    //end modal


    //crud data master
    public function crudMachine(Request $request, $id = null)
    {
        $tgl = \Carbon\Carbon::now();
        if ($request->isMethod('post')) {
            try {
                \DB::insert("INSERT INTO machine (machine_name,ket,created_at) VALUES ('$request->machine','$request->ket','$tgl')");
                return response()->json(['msg'=>'Data Berhasil Di Buat'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('put')) {
            try {
                \DB::update("UPDATE machine SET machine_name = '$request->machine', ket = '$request->ket', updated_at ='$tgl' WHERE id = ".$request->id_machine." ");
                return response()->json(['msg'=>'Data Berhasil Di Ubah'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('delete')) {
            try {
                \DB::update("UPDATE machine SET deleted_at = '$tgl' WHERE id = ".$id." ");
                return response()->json(['msg'=>'Data Berhasil Di Hapus'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function crudMeasure(Request $request, $id = null)
    {
        $tgl = \Carbon\Carbon::now();
        if ($request->isMethod('post')) {
            try {
                \DB::insert("INSERT INTO sat_ukuran (kode_satuan,nama_sat,simbol,id_machine,created_at) VALUES ('$request->kode_satuan','$request->nama_sat','$request->simbol','$request->id_machine','$tgl')");
                return response()->json(['msg'=>'Data Berhasil Di Buat'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('put')) {
            try {
                \DB::update("UPDATE sat_ukuran SET kode_satuan = '$request->kode_satuan', nama_sat = '$request->nama_sat',simbol ='$request->simbol',id_machine='$request->id_machine',updated_at ='$tgl' WHERE id = ".$request->id_measure." ");
                return response()->json(['msg'=>'Data Berhasil Di Ubah'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('delete')) {
            try {
                \DB::update("UPDATE sat_ukuran SET deleted_at = '$tgl' WHERE id = ".$id." ");
                return response()->json(['msg'=>'Data Berhasil Di Hapus'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function crudLogger(Request $request, $id = null)
    {
        $tgl = \Carbon\Carbon::now();
        if ($request->isMethod('post')) {
            try {
                \DB::insert("INSERT INTO logger (imei,hp,ket,id_user,created_at) VALUES ('$request->imei','$request->hp','$request->ket','$request->id_user','$tgl')");
                return response()->json(['msg'=>'Data Berhasil Di Buat'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('put')) {
            try {
                \DB::update("UPDATE logger SET imei = '$request->imei', hp = '$request->hp',ket ='$request->ket', id_user ='$request->id_user',updated_at ='$tgl' WHERE id = ".$request->id_logger." ");
                return response()->json(['msg'=>'Data Berhasil Di Ubah'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('delete')) {
            try {
                \DB::update("UPDATE logger SET deleted_at = '$tgl' WHERE id = ".$id." ");
                return response()->json(['msg'=>'Data Berhasil Di Hapus'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function crudUser(Request $request, $id = null)
    {
        $tgl = \Carbon\Carbon::now();
        if ($request->isMethod('post')) {
            try {
                $role_lab = 3;

                $user_lab = new User();
                $user_lab->name     = $request->name;
                $user_lab->password = bcrypt($request->password);
                $user_lab->email    = $request->email;
                $user_lab->api_token    = Str::random(60);
                $user_lab->save();
                $user_lab->attachRole($role_lab);
                return response()->json(['msg'=>'Data Berhasil Di Buat'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('put')) {
            try {
                \DB::update("UPDATE users SET `name` = '$request->name', email = '$request->email',updated_at ='$tgl' WHERE id = ".$request->id_user." ");
                return response()->json(['msg'=>'Data Berhasil Di Ubah'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('delete')) {
            try {
                \DB::update("UPDATE users SET deleted_at = '$tgl' WHERE id = ".$id." ");
                return response()->json(['msg'=>'Data Berhasil Di Hapus'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function crudPool(Request $request, $id = null)
    {
        $tgl = \Carbon\Carbon::now();
        if ($request->isMethod('post')) {
            try {
                \DB::insert("INSERT INTO `pool` (kolam,keterangan,created_at) VALUES ('$request->kolam','$request->keterangan','$tgl')");
                return response()->json(['msg'=>'Data Berhasil Di Buat'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('put')) {
            try {
                \DB::update("UPDATE `pool` SET kolam = '$request->kolam', keterangan = '$request->keterangan', updated_at ='$tgl' WHERE id = ".$request->id_kolam." ");
                return response()->json(['msg'=>'Data Berhasil Di Ubah'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($request->isMethod('delete')) {
            try {
                \DB::update("UPDATE `pool` SET deleted_at = '$tgl' WHERE id = ".$id." ");
                return response()->json(['msg'=>'Data Berhasil Di Hapus'],200);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    //end crud
}

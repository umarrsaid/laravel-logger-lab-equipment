@extends('template.index')
@push('title') Data Logging @endpush
@push('activity') open active @endpush
@push('selected_act') <span class="selected"></span>@endpush
@push('open_act') open @endpush

@push('active_data_log') open active @endpush
@push('selected_dlog')<span class="selected"></span>@endpush
@section('content')
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <!-- BEGIN PAGE TITLE -->
    <div class="page-title">
        <h1>Data Logging
            <small></small>
        </h1>
    </div>
    <!-- END PAGE TITLE -->
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE BREADCRUMB -->
<ul class="page-breadcrumb breadcrumb">
    <li>
        <i class="fa fa-circle"></i>
        <a href="#">Activity</a>
    </li>
    <li>
        <i class="fa fa-circle"></i>
        <a href="#">Data Logging</a>
    </li>
</ul>
<!-- END PAGE BREADCRUMB -->
<!-- BEGIN PAGE BASE CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">

            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-sm-4" style="width:30%">
                            <div class="input-group date-picker input-daterange" data-date-format="dd-mm-yyyy">
                                <input type="text" autocomplete="off" class="form-control" name="from" placeholder="dd-mm-yyyy" id="tgl_awal">
                                <span class="input-group-addon"> to </span>
                                <input type="text" autocomplete="off" class="form-control" name="to" placeholder="dd-mm-yyyy" id="tgl_akhir">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="input-group select2-bootstrap-prepend">
                                    <span class="input-group-addon">
                                        Satuan
                                    </span>
                                    <select class="form-control select2" id="satuan">
                                        <option value="all">Semua satuan</option>
                                        {!! \Logger::dropdownPengukuran('satuan') !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="input-group select2-bootstrap-prepend">
                                    <span class="input-group-addon">
                                        Kolam
                                    </span>
                                    <select class="form-control select2" id="kolam">
                                        <option value="all">Semua kolam</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <a href="javascript:;" class="btn blue" id="print">
                                    <i class="fa fa-print"></i> Excel
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <a href="javascript:;" class="btn red" id="hapus">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="{{Auth::user()->id}}" id="id_user">
                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="table-log">
                    <thead>
                        <tr>
                            <th width="5%"> No </th>
                            <th> Tanggal </th>
                            <th> Value </th>
                            <th> Satuan </th>
                            <th> Kolam </th>
                            <th> User </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE BASE CONTENT -->
@endsection
@push('js')
<script>
    var id_user = $('#id_user').val();
    $('#satuan').prop('disabled', true);
    $('#kolam').prop('disabled', true);

    //cek data datatable
	function timeout_datatable(){
		var times = setTimeout( function () {
			oTable.ajax.reload();
		}, 1000 );
		count++;
		if(count == 10){
			clearTimeout(times);
			swal(
				'Periksa Koneksi',
				'Gagal mengambil data, terjadi gangguan koneksi.',
				'warning'
				)
		}
	}

	//datatable
	$(function () {
        showLoading();
		setTimeout(function(){
            oTable = $('#table-log').DataTable({
                lengthMenu    : [[25, 50, 75, 100], [25, 50, 75, 100]],

                serverSide    :true,
                    bScrollCollapse: true,
                    ordering      :false,
                    processing:     true,
                    ajax: {
                        url: "/table/activity/data-log/"+id_user,
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        timeout_datatable();
                    },
                    columns: [
                        {data: 'DT_RowIndex', searchable: false},
                        {data: 'tgl_jam'},
                        {data: 'value'},
                        {data: 'satuan'},
                        {data: 'kolam'},
                        {data: 'alat'}
                    ]
                });
        hideLoading();
        }, 1000);
	});

    //export excel
    $('#tgl_awal,#tgl_akhir,#satuan,#kolam').on('change',function(){
        var tgl_awal = $('#tgl_awal').val();
        var tgl_akhir = $('#tgl_akhir').val();
        var satuan = $('#satuan').val();
        var kolam = $('#kolam').val();

        var s = satuan.replace('/','_');
        var k = kolam.replace('/','_');
        if (tgl_awal && tgl_akhir) {
            $('#satuan').prop('disabled', false);
            var new_url = "/table/activity/data-log/"+id_user+"/"+tgl_awal+"/"+tgl_akhir+"/"+s+"/"+k;
            showLoading();
            $('#table-log').DataTable().ajax.url(new_url).load();
            hideLoading();
        }
    });

    $('#satuan').on('change',function(){
        var satuan = $(this).val();
        $.ajax({
            type: 'get',
            url: '/logger/data-kolam',
            dataType: 'json',
            data: {
                satuan:satuan,
                id_user:id_user
            },
            success: function(data) {
                $('#kolam').html('');
                $('#kolam').prop('disabled', false);
                $('#kolam').append('<option value="all">Semua Kolam</option>');
                $('#kolam').append(data.data);
            },
        });
    });

    $(document).on('click','#print',function(){
        var tgl_awal = $('#tgl_awal').val();
        var tgl_akhir = $('#tgl_akhir').val();
        var satuan = $('#satuan').val();
        var kolam = $('#kolam').val();

        var value_table = oTable.data().count();

        if (value_table > 0) {
            if (tgl_awal && tgl_akhir) {
                $.ajax({
                    type: 'get',
                    url: '/logger/report-data',
                    dataType: 'json',
                    data: {
                        id_user:id_user,
                        tgl_awal: tgl_awal,
                        tgl_akhir:tgl_akhir,
                        satuan:satuan,
                        kolam:kolam
                    },
                    success: function(data) {
                        window.open(data.link, '_blank');
                    },
                });
            }else{
                swal({
                    title :'Warning !',
                    text: 'Tanggal awal dan akhir tidak boleh kosong.',
                    type: "warning",
                    timer: 2000
                })
            }
        }else{
            swal({
                    title :'Warning !',
                    text: 'Tidak ada yang dapat di cetak',
                    type: "warning",
                    timer: 2000
                })
        }
    });

    //tanggal
        $(document).ready(function(){
            $('#tgl_awal').on("cut paste",function(e) {
            e.preventDefault();
            });
        });

        $('#tgl_awal').keypress(function(e) {
            return false
        });

        $('#tgl_awal').keydown(function(e) {
            if (e.keyCode === 8 || e.keyCode === 46) {
                return false;
            }
        });

        $(document).ready(function(){
            $('#tgl_akhir').on("cut paste",function(e) {
            e.preventDefault();
            });
        });

        $('#tgl_akhir').keypress(function(e) {
            return false
        });

        $('#tgl_akhir').keydown(function(e) {
            if (e.keyCode === 8 || e.keyCode === 46) {
                return false;
            }
        });
    //

    $(document).on('click','#hapus',function(){
        var tgl_awal = $('#tgl_awal').val();
        var tgl_akhir = $('#tgl_akhir').val();
        var satuan = $('#satuan').val();
        var kolam = $('#kolam').val();

        var value_table = oTable.data().count();

        if (tgl_awal && tgl_akhir && satuan && kolam) {
            if (value_table > 0) {
                bootbox.dialog({
                    title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Data Logging ',
                    message: "Anda yakin akan menghapus Data ini ? ,Data yang sudah dihapus tidak akan bisa dikembalikan lagi.",
                    buttons: {
                        confirm: {
                            label: '<i class="fa fa-trash"></i> Hapus',
                            className: 'btn btn-default btn-danger',
                            callback: function(){
                                showLoading();
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    type: 'POST',
                                    url: "/logger/delete",
                                    data:{
                                        id_user:id_user,
                                        tgl_awal:tgl_awal,
                                        tgl_akhir:tgl_akhir,
                                        satuan:satuan,
                                        kolam:kolam
                                    },
                                    success: function(data) {
                                        if (data.errors) {
                                            swal({
                                                title :'Gagal',
                                                text: 'Data Gagal Dihapus.',
                                                type: "error"
                                            })
                                            hideLoading();
                                        } else {
                                            $('#table-log').DataTable().ajax.reload();
                                            swal({
                                                title :'Sukses',
                                                text: 'Data Berhasil Dihapus.',
                                                type: "success",
                                                timer: 2000
                                            })
                                            hideLoading();
                                        }
                                    },
                                });
                            }
                        },
                        cancel: {
                        label: '<i class="fa fa-times"></i> Batal',
                        className: 'btn btn-default'
                    },
                    }
                });
            }else{
                swal({
                    title :'Warning !',
                    text: 'Tidak ada yang dapat di hapus',
                    type: "warning",
                    timer: 2000
                })
            }
        }else{
            swal({
                title :'Warning !',
                text: 'Tanggal awal dan Tanggal akhir tidak boleh kosong',
                type: "warning",
                timer: 2000
            })
        }
    });
</script>
@endpush

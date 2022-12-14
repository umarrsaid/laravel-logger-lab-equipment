@extends('template.index')
@push('title') Location @endpush
@push('data-master') open active @endpush
@push('selected_dm') <span class="selected"></span>@endpush
@push('open_dm') open @endpush

@push('active_pool') open active @endpush
@push('selected_pol')<span class="selected"></span>@endpush
@section('content')
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <!-- BEGIN PAGE TITLE -->
    <div class="page-title">
        <h1>Location
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
        <a href="#">Data Master</a>
    </li>
    <li>
        <i class="fa fa-circle"></i>
        <a href="#">Location</a>
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
                        <div class="col-md-6">
                            <div class="btn-group">
                                <button class="btn sbold green modal-pool" data-name="modal-pool"> Add New
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="table-pool">
                    <thead>
                        <tr>
                            <th width="5%"> No </th>
                            <th width="10%"> Action </th>
                            <th> Location </th>
                            <th> Description </th>
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
        showLoading()
		setTimeout(function(){
            oTable = $('#table-pool').DataTable({
                lengthMenu    : [[10, 25, 50, -1], [10, 25, 50, "All"]],

                serverSide    :true,
                    bScrollCollapse: true,
                    ordering      :false,
                    processing:     true,
                    ajax: {
                        url: "/table/data-master/pool",
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        timeout_datatable();
                    },
                    columns: [
                        {data: 'DT_RowIndex', searchable: false},
                        {data: 'aksi',searchable: false},
                        {data: 'kolam'},
                        {data: 'keterangan'},
                    ]
                });
            hideLoading()
        }, 1000);
	});

    //modal
    $(document).on('click', '.modal-pool', function() {
        showLoading()
		$.ajax({
			url: '/modal-dm',
            type: 'GET',
            data: {
                id: $(this).attr('data-id'),
                name: $(this).attr('data-name'),
            }
		})
		.done(function(data) {
			$('.modal-dialog').addClass(data.modal_size);
			$('.modal-title').html(data.modal_header);
			$('.modal-body').html(data.modal_body);
			$('.modal-footer').html(data.modal_footer);
			$('#myModal').modal({'backdrop': 'static'});
			$('#myModal').modal('show');
		})
		.fail(function(data) {
		})
		.always(function(data) {
            hideLoading()
		});
	});

    $('#form').submit( function(e) {
        showLoading()
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				'Authorization': "Bearer "+$('meta[name=bearer-token]').attr('content')
			}
		});

		$( "#btn-save" ).prop( "disabled", true );

        switch ($("button:submit").data('ref')) {
            case'save-pool':
                $.ajax({
                    type: 'POST',
                    url: "/crud/pool",
                    data: $('#form').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $('#myModal').modal('hide');
                        $('#table-pool').DataTable().ajax.reload();
                        swal({
                            title :'Sukses',
                            text: 'Data Berhasil Di Buat.',
                            type: "success",
                            timer: 2000
                        })
                        $( "#btn-save" ).prop( "disabled", false );
                        hideLoading()
                    },
                    error: function (data) {
                        $( "#btn-save" ).prop( "disabled", false );
                        hideLoading()
                    }
                });
            break;

            case'update-pool':
                $.ajax({
                    type: 'PUT',
                    url: "/crud/pool",
                    data: $('#form').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        $('#myModal').modal('hide');
                        $('#table-pool').DataTable().ajax.reload();
                        swal({
                            title :'Sukses',
                            text: 'Data Berhasil Di Ubah.',
                            type: "success",
                            timer: 2000
                        })
                        $( "#btn-save" ).prop( "disabled", false );
                        hideLoading()
                    },
                    error: function (data) {
                        $( "#btn-save" ).prop( "disabled", false );
                        hideLoading()
                    }
                });
            break;
        }
    });

    $(document).on('click', '#delete', function() {
	    var id = $(this).attr("data-id");
	    bootbox.dialog({
	        title: '<span class="orange"><i class="fa fa-trash"></i> Hapus</span> Data ini',
	        message: "Anda yakin akan menghapus Data ini ?",
	        buttons: {
	            confirm: {
	                label: '<i class="fa fa-trash"></i> Hapus',
	                className: 'btn btn-default btn-danger',
	                callback: function(){
	                    $.ajaxSetup({
	                        headers: {
	                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                        }
	                    });
	                    $.ajax({
	                        type: 'delete',
	                        url: "/crud/pool/"+id,
	                        data: $("#form").serialize(),
	                        success: function(data) {
	                            var tabel = $('#table-pool').DataTable();
	                            tabel.ajax.reload();
                                if (data.errors) {
                                    swal({
                                        title :'Gagal',
                                        text: 'Data Gagal Dihapus.',
                                        type: "error",
                                        timer: 2000
                                    })
                                } else {
                                    swal({
                                        title :'Sukses',
                                        text: 'Data Berhasil Dihapus.',
                                        type: "success",
                                        timer: 2000
                                    })
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
	});
</script>
@endpush

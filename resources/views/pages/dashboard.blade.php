@extends('template.index')
@push('title') Dashboard @endpush
@push('dashboard') open active @endpush
@section('content')
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <!-- BEGIN PAGE TITLE -->
    <div class="page-title">
        <h1>Dashboard
        </h1>
    </div>
    <!-- END PAGE TITLE -->
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE BREADCRUMB -->
<ul class="page-breadcrumb breadcrumb">
    <li>
        <i class="fa fa-circle"></i>
        <a href="#">Dashboard</a>
    </li>
</ul>
<!-- END PAGE BREADCRUMB -->
<!-- BEGIN PAGE BASE CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-calendar"></i>
                                <input id="tgl_awl" placeholder="Day-Month-Year" data-date-format="dd-mm-yyyy" class="form-control date-picker tgl" type="text" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-clock-o"></i>
                                <input id="time1" placeholder="Hour : Minute"  type="text" class="form-control timepicker timepicker-24">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-calendar"></i>
                                <input id="tgl_akh" placeholder="Day-Month-Year" data-date-format="dd-mm-yyyy" class="form-control date-picker tgl" type="text" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-clock-o"></i>
                                <input id="time2" name="time2" placeholder="Hour : Minute" type="text" class="form-control timepicker timepicker-24">
                                <span class="help-block has-error waktu_akhir"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-info" id="filter"><i class="fa fa-filter"></i>Filter</button>
                        </div>
                    </div>
                </div><br>
                <input type="hidden" value="{{Auth::user()->id}}" id="id_user">
                <div class="row">
                    <div class="tabbable-custom ">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active">
                                <a href="#box_tab1" id="1" data-name="Suhu" data-toggle="tab"><span class="caption-subject font-green bold">°C</span></a>
                            </li>
                            <li>
                                <a href="#box_tab2" id="2" data-name="pH" data-toggle="tab"><span class="caption-subject font-green bold">pH</span></a>
                            </li>
                            <li>
                                <a href="#box_tab3" id="3" data-name="% 02 Di Udara" data-toggle="tab"><span class="caption-subject font-green bold">O2</a>
                            </li>
                            <li>
                                <a href="#box_tab4" id="4" data-name="Oksigen (miligram/liter)" data-toggle="tab"><span class="caption-subject font-green bold">mg/l</a>
                            </li>
                            <li>
                                <a href="#box_tab5" id="5" data-name="Konduktivitas (MikroSiemen per Centimeter)" data-toggle="tab"><span class="caption-subject font-green bold">µs</a>
                            </li>
                            <li>
                                <a href="#box_tab6" id="6" data-name="Meter/Sekon" data-toggle="tab"><span class="caption-subject font-green bold">m/s</a>
                            </li>
                            <li>
                                <a href="#box_tab7" id="7" data-name="pH/mv(milivolt)" data-toggle="tab"><span class="caption-subject font-green bold">mV</a>
                            </li>
                            <li>
                                <a href="#box_tab8" id="8" data-name="TDS(part per million)" data-toggle="tab"><span class="caption-subject font-green bold">ppm</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="portlet-body">
                                    <div id="chart" style="height:500px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    var id_tab = 1;

    $('#myTab a').click(function (e) {
        var id = $(this).attr("id");
        id_tab = id;
        var name = $(this).attr("data-name");
        var datas = 0;
        var tgl_awl = $('#tgl_awl').val();
        var tgl_akh = $('#tgl_akh').val();
        var jam_aw = $('#time1').val();
        var jam_ak = $('#time2').val();
        chartPengukuran(id_tab,name,datas,tgl_awl,tgl_akh,jam_aw,jam_ak)
        $('#chart').highcharts().redraw();
    });

    function chartPengukuran(id_tab,name,datas,tgl_awl,tgl_akh,jam_aw,jam_ak) {
        showLoading();
        // +'&jam_awal='+jam_aw+'&jam_akhir='+jam_ak
        $.getJSON('{{ route("chart")}}?id='+id_tab+'&id_user='+id_user+'&tgl_awl='+tgl_awl+'&jam_awal='+jam_aw+'&tgl_akh='+tgl_akh+'&jam_akhir='+jam_ak,function(data){
            if (datas) {
                data = datas;
            }

            Highcharts.setOptions({
                global: {
                    useUTC: false
                }
            });

            $('#chart').highcharts({
                title: {
                    text: 'Data Pengukuran '+name
                },
                xAxis: {
                        categories: data['jam']
                },
                plotOptions: {
                    column: {
                        stacking: 'normal'
                    }
                },
                series: data['data'],
                exporting: {
                    enabled: true,
                }
            });
        }).done(function() {
            hideLoading();
        })
        .fail(function() {
            hideLoading();
            $('#chart').highcharts().redraw();
        })
        .always(function() {

        });
    }

    $(document).ready(function(){
        //time format
        $('#time1').val('0:50');
        $('#time2').val('23:50');
        //

        //date format
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        var tanggal = day+'-'+month+'-'+year;
        $('#tgl_awl').val(tanggal);
        $('#tgl_akh').val(tanggal);
        //

        //jam
            var jam_aw = '0:50';
            var jam_ak = '23:50';
        //

        chartPengukuran(1,'Suhu',0,tanggal,tanggal,jam_aw,jam_ak)
    });

    //tanggal awal
    $(document).ready(function(){
        $('.tgl').on("cut paste",function(e) {
            e.preventDefault();
        });

        $('.tgl').keydown(function(e) {
            if (e.keyCode === 8 || e.keyCode === 46) {
            return false;
            }
        });

        $('.tgl').keypress(function(e) {
            return false
        });

        $('.time').on("cut paste",function(e) {
            e.preventDefault();
        });

        $('.time').keydown(function(e) {
            if (e.keyCode === 8 || e.keyCode === 46) {
            return false;
            }
        });

        $('.time').keypress(function(e) {
            return false
        });

    });
    //end tanggal awal
    $(document).on('click','#filter',function(){
        var tanggal_awl = $('#tgl_awl').val();
        var tanggal_akh = $('#tgl_akh').val();
        var waktu_awal = $('#time1').val();
        var waktu_akhir = $('#time2').val();
        // console.log(waktu_awal+' '+waktu_akhir);
        var name = $('#myTab a').attr("data-name");
        var sp1 = waktu_awal.split(":");
        var sp2 = waktu_akhir.split(":");

        if (parseInt(sp2[0]) < parseInt(sp1[0])) {
            $('input,textarea,select').on('keydown keypress keyup click change',function(){
                $(this).parent().removeClass('has-error');
                $(this).next('.help-block').hide()
            });

            $('.form-group').removeClass('has-error');

            $('[name=time2]').parent().addClass('has-error');
            $('[name=time2]').next('.help-block').show().text('Harus melebihi waktu awal');
        }else{
            var selisih = sp2[0] - sp1[0];
            var jml_m = parseInt(sp2[1]) + parseInt(sp1[1]);
            if (sp1[0] == sp2[0] || selisih == 1) {
                if (sp1[1] != sp2[1] && selisih == 1) {
                    if (sp2[1] < sp1[1]) {
                        $('input,textarea,select').on('keydown keypress keyup click change',function(){
                            $(this).parent().removeClass('has-error');
                            $(this).next('.help-block').hide()
                        });

                        $('.form-group').removeClass('has-error');

                        $('[name=time2]').parent().addClass('has-error');
                        $('[name=time2]').next('.help-block').show().text('Harus lebih dari 60 menit');
                    }
                }else if (sp1[0] == sp2[0]) {
                    $('input,textarea,select').on('keydown keypress keyup click change',function(){
                        $(this).parent().removeClass('has-error');
                        $(this).next('.help-block').hide()
                    });

                    $('.form-group').removeClass('has-error');

                    $('[name=time2]').parent().addClass('has-error');
                    $('[name=time2]').next('.help-block').show().text('Harus lebih dari 60 menit');
                }else{
                    $.ajax({
                        type: "GET",
                        url: "/logger/filter/"+id_tab+'/'+id_user+'/'+tanggal_awl+'/'+waktu_awal+'/'+tanggal_akh+'/'+waktu_akhir,
                        success: function(data){
                            chartPengukuran(id_tab,name,data,tanggal_awl,tanggal_akh,waktu_awal,waktu_akhir)
                            $('#chart').highcharts().redraw();
                        },
                        error: function(){

                        }
                    });
                }
            }else{
                $.ajax({
                    type: "GET",
                    url: "/logger/filter/"+id_tab+'/'+id_user+'/'+tanggal_awl+'/'+waktu_awal+'/'+tanggal_akh+'/'+waktu_akhir,
                    success: function(data){
                        chartPengukuran(id_tab,name,data,tanggal_awl,tanggal_akh,waktu_awal,waktu_akhir)
                        $('#chart').highcharts().redraw();
                    },
                    error: function(){

                    }
                });
            }
        }
    });

    $(document).on('change','#tgl_awl, #tgl_akh', function(){
        var tanggal_awl = $('#tgl_awl').val();
        var tanggal_akh = $('#tgl_akh').val();
        var waktu_awal = $('#time1').val();
        var waktu_akhir = $('#time2').val();
        // console.log(waktu_awal+' '+waktu_akhir);
        var name = $('#myTab a').attr("data-name");
        var sp1 = waktu_awal.split(":");
        var sp2 = waktu_akhir.split(":");

        if (parseInt(sp2[0]) < parseInt(sp1[0])) {
            $('input,textarea,select').on('keydown keypress keyup click change',function(){
                $(this).parent().removeClass('has-error');
                $(this).next('.help-block').hide()
            });

            $('.form-group').removeClass('has-error');

            $('[name=time2]').parent().addClass('has-error');
            $('[name=time2]').next('.help-block').show().text('Harus melebihi waktu awal');
        }else{
            var selisih = sp2[0] - sp1[0];
            var jml_m = parseInt(sp2[1]) + parseInt(sp1[1]);
            if (sp1[0] == sp2[0] || selisih == 1) {
                if (sp1[1] != sp2[1] && selisih == 1) {
                    if (sp2[1] < sp1[1]) {
                        $('input,textarea,select').on('keydown keypress keyup click change',function(){
                            $(this).parent().removeClass('has-error');
                            $(this).next('.help-block').hide()
                        });

                        $('.form-group').removeClass('has-error');

                        $('[name=time2]').parent().addClass('has-error');
                        $('[name=time2]').next('.help-block').show().text('Harus lebih dari 60 menit');
                    }
                }else if (sp1[0] == sp2[0]) {
                    $('input,textarea,select').on('keydown keypress keyup click change',function(){
                        $(this).parent().removeClass('has-error');
                        $(this).next('.help-block').hide()
                    });

                    $('.form-group').removeClass('has-error');

                    $('[name=time2]').parent().addClass('has-error');
                    $('[name=time2]').next('.help-block').show().text('Harus lebih dari 60 menit');
                }else{
                    $.ajax({
                        type: "GET",
                        url: "/logger/filter/"+id_tab+'/'+id_user+'/'+tanggal_awl+'/'+waktu_awal+'/'+tanggal_akh+'/'+waktu_akhir,
                        success: function(data){
                            chartPengukuran(id_tab,name,data,tanggal_awl,tanggal_akh,waktu_awal,waktu_akhir)
                            $('#chart').highcharts().redraw();
                        },
                        error: function(){

                        }
                    });
                }
            }else{
                $.ajax({
                    type: "GET",
                    url: "/logger/filter/"+id_tab+'/'+id_user+'/'+tanggal_awl+'/'+waktu_awal+'/'+tanggal_akh+'/'+waktu_akhir,
                    success: function(data){
                        chartPengukuran(id_tab,name,data,tanggal_awl,tanggal_akh,waktu_awal,waktu_akhir)
                        $('#chart').highcharts().redraw();
                    },
                    error: function(){

                    }
                });
            }
        }
    });
</script>
@endpush

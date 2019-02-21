@extends('layouts.app')

@section('content')
<div class="form-group">
    <div class="row justify-content-center">
        <div class="col-sm-11">
            <div class="row justify-content-center">
                <div class="col-sm-5">
                    <div class="alert alert-warning" role="alert">
                        <b>
                            @guest
                            <ul>
                                <li>Kalau ada error jangan langsung direfresh, tanya dulu</li>
                                <li>Kalau sudah selesai, boleh lanjut ke donasi atau yang lainnya</li>
                            </ul>
                            @endguest
                            @auth
                            <center>
                                HARAP MEMERIKSA KEMBALI <a href="{{ route('select') }}">DATA CALEG</a> SEBELUM DI EXPORT,
                                <br>
                                SETELAH EXPORT TIDAK DAPAT DI CHECK LAGI.
                            </center>
                            @endauth
                        </b>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="row justify-content-center">
                            <div class="col-sm-5">
                                <input type="number" name="row" id="row" class="form-control" autofocus required>
                            </div>
                            <button id="add" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                    <form class="form-group" method="POST" action="{{ route('create') }}">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-sm-10">
                                <div id="isi" style="display: none;">
                                    <div class="form-group jumbotron">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <label>Nama Provinsi</label>
                                                <select name="nama_provinsi" class="select2 form-control" style="width: 100%;" required>
                                                    <option value="" selected disabled> - PILIH PROVINSI -  </option>
                                                    @foreach($provinces as $province)
                                                    <option value="{{ $province->provinsi_nama }}">{{ $province->provinsi_nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <div id="dapil"></div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-10" id="button"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot-content')
<script type="text/javascript">    
    $(document).on('click','#add',function(e){
        var id = e.target.value;
        var row = $('#row').val();
        if (row == '' || row == null)
        {
            $('#isi').hide();
            $('#dapil').empty();
            $('#button').empty();
        }
        else {
            $('#isi').show();
            $('#dapil').empty();
            $('#button').empty();
            for (var i = 0; i < row; i++) {
                $('#dapil').append('<div class="form-group"><div class="row justify-content-center"><div class="col-sm-9"><label>Nama Dapil</label><select name="nama_dapil[]" class="select2 form-control" style="width: 100%;" required><option value="" selected disabled> - PILIH DAPIL -  </option>@foreach($semua_dapil as $dapil)<option value="{{ $dapil->dapil_nama }}">{{ $dapil->dapil_nama }}</option>@endforeach</select></div><div class="col-sm-3"><label>Jumlah Caleg</label><input type="number" name="dapil_row[' + i + ']" id="dapil_' + i + '_row" class="form-control" required></div></div></div>');
            }
            $('select').select2();
            $('#button').append('<button type="submit" class="btn btn-secondary btn-lg btn-block">Buat</button>');
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select').select2();
    });
</script>
@endsection

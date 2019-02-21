@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-11">
        <div class="card">
            <div class="card-header">Dashboard</div>

            <div class="card-body">
                <div class="form-group">
                    <center>
                        <h1>Lapor Dapil Kosong</h1>
                    </center>
                </div>
                <div class="form-group">
                    <div class="row justify-content-center">
                        <div class="col-sm-5">
                            <label>Jumlah Dapil</label>
                            <input type="number" name="row" id="row" class="form-control" autofocus required>
                        </div>
                        <button id="add" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
                <form class="form-group" method="POST" action="{{ route('laporPost') }}">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-sm-10">
                            <div id="isi">
                                <div class="form-group jumbotron">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Nama Provinsi</label>
                                                <select name="provinsi" class="select2 form-control" style="width: 100%;" required>
                                                    <option value=""> - PILIH PROVINSI - </option>
                                                    @foreach($provinces as $province)
                                                    <option value="{{ $province->provinsi_nama }}">{{ $province->provinsi_nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Kabupaten</label>
                                                <input type="text" onkeyup="this.value = this.value.toUpperCase();" name="kabupaten" class="form-control" required>
                                            </div>
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
                $('#dapil').append('<div class="form-group"><div class="row justify-content-center"><div class="col-sm-9"><label>Nama Dapil</label><select name="dapil[]" class="select2 form-control" style="width: 100%;">@foreach($semua_dapil as $dapil)<option value="{{ $dapil->dapil_nama }}">{{ $dapil->dapil_nama }}</option>@endforeach</select></div></div></div>');
            }
            $('select').select2();
            $('#button').append('<button style="color:white;" type="submit" class="btn btn-warning btn-lg btn-block">Lapor</button>');
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


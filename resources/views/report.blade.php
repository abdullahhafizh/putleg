@extends('layouts.app')

@section('content')
<div class="form-group">
    <div class="row justify-content-center">
        <div class="col-sm-11">
            <div class="card card-warning">
                <div class="card-header">Dapil yang belum terisi</div>

                <div class="card-body">
                    <div class="row">
                        @foreach($un_dapil as $dapil)
                        <div class="col-sm-3">
                            {{ $dapil }}
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <br>
                                <div class="jumbotron">
                                    <center>
                                        <h1><b id="total">Total : {{ $total }} Dapil</b></h1>
                                    </center>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <center>
                                                        <h2><b id="progress">Progress : {{ $progress }}%</b></h2>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <center>
                                                        <h2><b id="selesai">Selesai : {{ $selesai }}%</b></h2>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot-content')
<script type="text/javascript">
    setInterval(function()
    {
        $.get("{{ route('total') }}",function(data) {
            console.log(data);
            $('#total').empty();
            $('#total').append('Total : ' + data + ' Dapil');            
        });
        $.get("{{ route('progress') }}",function(data) {
            console.log(data);
            $('#progress').empty();
            $('#progress').append('Progress : ' + data + '%');            
        });
        $.get("{{ route('selesai') }}",function(data) {
            console.log(data);
            $('#selesai').empty();
            $('#selesai').append('Selesai : ' + data + '%');            
        });
    }, 10000);
</script>
@endsection


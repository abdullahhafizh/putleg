@extends('layouts.app')

@section('content')
<div class="form-group">
    <div class="row justify-content-center">
        <div class="col-sm-11">
            <div class="card">
                <div class="card-header">Ada caleg yang gambarnya belum diinput, tolong bantu yaa...</div>

                <div class="card-body">                    
                    <form class="form-group" method="POST" action="{{ route('donasi') }}">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-sm-12">
                                <div>
                                    <div class="form-group">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">
                                                <div class="table-responsive responsive-table">
                                                    <table class="table table-bordered table-hover table-striped table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th>Provinsi</th>
                                                                <!-- <th>Kabupaten</th> -->
                                                                <th>Dapil</th>
                                                                <th>No. Urut</th>
                                                                <th>Nama Caleg</th>
                                                                <th>Jenis Kelamin</th>
                                                                <th>Alamat</th>
                                                                <th>Isi link url disini</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($semua_caleg as $caleg)
                                                            <tr>
                                                                <td>{{ $caleg->provinsi }}</td>
                                                                <!-- <td>{{ $kabupaten }}</td> -->
                                                                <td>{{ $caleg->dapil }}</td>
                                                                <th align="center"><center>{{ $caleg->no_urut }}</center></th>
                                                                <td>{{ $caleg->nama_caleg }}</td>
                                                                @if($caleg->jenkel == "L")
                                                                <td>Laki-laki</td>
                                                                @elseif($caleg->jenkel == "P")
                                                                <td>Perempuan</td>
                                                                @else
                                                                <td>?</td>
                                                                @endif
                                                                <td>{{ $caleg->alamat }}</td>
                                                                <td>
                                                                    <input type="url" name="url_file[]" class="form-control" required>
                                                                    <input type="hidden" name="id[]" value="{{ $caleg->id }}">
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-sm-12">                                                
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-block btn-lg">Donasi</button>
                                                    <center><small>BANTUAN ANDA SANGAT BERARTI BAGI KAMI</small></center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
